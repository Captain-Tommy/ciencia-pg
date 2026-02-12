<?php

namespace Ciencia\Services\Application\Handlers\Order\Payment\Stripe;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Ciencia\DomainObjects\AccountConfigurationDomainObject;
use Ciencia\DomainObjects\AccountStripePlatformDomainObject;
use Ciencia\DomainObjects\AccountVatSettingDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Generated\StripePaymentDomainObjectAbstract;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Exceptions\Stripe\CreatePaymentIntentFailedException;
use Ciencia\Exceptions\UnauthorizedException;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Repository\Interfaces\StripePaymentsRepositoryInterface;
use Ciencia\Services\Domain\Payment\Stripe\DTOs\CreatePaymentIntentRequestDTO;
use Ciencia\Services\Domain\Payment\Stripe\DTOs\CreatePaymentIntentResponseDTO;
use Ciencia\Services\Domain\Payment\Stripe\StripePaymentIntentCreationService;
use Ciencia\Services\Infrastructure\Session\CheckoutSessionManagementService;
use Ciencia\Services\Infrastructure\Stripe\StripeClientFactory;
use Ciencia\Services\Infrastructure\Stripe\StripeConfigurationService;
use Ciencia\Values\MoneyValue;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Throwable;

readonly class CreatePaymentIntentHandler
{
    public function __construct(
        private OrderRepositoryInterface           $orderRepository,
        private StripePaymentIntentCreationService $stripePaymentService,
        private CheckoutSessionManagementService   $sessionIdentifierService,
        private StripePaymentsRepositoryInterface  $stripePaymentsRepository,
        private AccountRepositoryInterface         $accountRepository,
        private StripeClientFactory                $stripeClientFactory,
        private StripeConfigurationService         $stripeConfigurationService,
    )
    {
    }

    /**
     * @param string $orderShortId
     * @return CreatePaymentIntentResponseDTO
     * @throws CreatePaymentIntentFailedException
     * @throws MathException
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     * @throws UnknownCurrencyException
     * @throws ApiErrorException
     * @throws Throwable
     */
    public function handle(string $orderShortId): CreatePaymentIntentResponseDTO
    {
        $order = $this->orderRepository
            ->loadRelation(new Relationship(OrderItemDomainObject::class))
            ->loadRelation(new Relationship(StripePaymentDomainObject::class, name: 'stripe_payment'))
            ->loadRelation(new Relationship(EventDomainObject::class, name: 'event'))
            ->findByShortId($orderShortId);

        if (!$order || !$this->sessionIdentifierService->verifySession($order->getSessionId())) {
            throw new UnauthorizedException(__('Sorry, we could not verify your session. Please create a new order.'));
        }

        if ($order->getStatus() !== OrderStatus::RESERVED->name || $order->isReservedOrderExpired()) {
            throw new ResourceConflictException(__('Sorry, is expired or not in a valid state.'));
        }

        $account = $this->accountRepository
            ->loadRelation(new Relationship(
                domainObject: AccountConfigurationDomainObject::class,
                name: 'configuration',
            ))
            ->loadRelation(AccountStripePlatformDomainObject::class)
            ->loadRelation(new Relationship(
                domainObject: AccountVatSettingDomainObject::class,
                name: 'account_vat_setting',
            ))
            ->findByEventId($order->getEventId());

        $stripePlatform = $account->getActiveStripePlatform()
            ?? $this->stripeConfigurationService->getPrimaryPlatform();

        $stripeAccountId = $account->getActiveStripeAccountId();

        // If no platform is configured, we can still process payments with regular Stripe keys
        if (!$stripePlatform) {
            $stripePlatform = null; // This will use default keys in StripeClientFactory
        }

        $stripeClient = $this->stripeClientFactory->createForPlatform($stripePlatform);
        $publicKey = $this->stripeConfigurationService->getPublicKey($stripePlatform);

        // If we already have a Stripe session then re-fetch the client secret
        if ($order->getStripePayment() !== null) {
            return new CreatePaymentIntentResponseDTO(
                paymentIntentId: $order->getStripePayment()->getPaymentIntentId(),
                clientSecret: $this->stripePaymentService->retrievePaymentIntentClientSecretWithClient(
                    $stripeClient,
                    $order->getStripePayment()->getPaymentIntentId(),
                    $stripeAccountId
                ),
                accountId: $stripeAccountId,
                stripePlatform: $stripePlatform,
                publicKey: $publicKey,
            );
        }

        $description = __(':item_count item(s) for event: :event_name (Order :order_short_id)', [
            'event_name' => Str::limit($order->getEvent()?->getTitle() ?? __('Event'), 75),
            'order_short_id' => $orderShortId,
            'item_count' => $order->getOrderItems()->sum(fn(OrderItemDomainObject $item) => $item->getQuantity()),
        ]);

        $paymentIntent = $this->stripePaymentService->createPaymentIntentWithClient(
            $stripeClient,
            CreatePaymentIntentRequestDTO::fromArray([
                'amount' => MoneyValue::fromFloat($order->getTotalGross(), $order->getCurrency()),
                'currencyCode' => $order->getCurrency(),
                'account' => $account,
                'order' => $order,
                'stripeAccountId' => $stripeAccountId,
                'vatSettings' => $account->getAccountVatSetting(),
                'description' => Str::limit($description, 997),
            ])
        );

        $applicationFeeData = $paymentIntent->applicationFeeData;

        $this->stripePaymentsRepository->create([
            StripePaymentDomainObjectAbstract::ORDER_ID => $order->getId(),
            StripePaymentDomainObjectAbstract::PAYMENT_INTENT_ID => $paymentIntent->paymentIntentId,
            StripePaymentDomainObjectAbstract::CONNECTED_ACCOUNT_ID => $stripeAccountId,
            StripePaymentDomainObjectAbstract::APPLICATION_FEE_GROSS => $applicationFeeData?->grossApplicationFee?->toMinorUnit() ?? 0,
            StripePaymentDomainObjectAbstract::APPLICATION_FEE_NET => $applicationFeeData?->netApplicationFee?->toMinorUnit() ?? 0,
            StripePaymentDomainObjectAbstract::APPLICATION_FEE_VAT => $applicationFeeData?->applicationFeeVatAmount?->toMinorUnit() ?? 0,
            StripePaymentDomainObjectAbstract::APPLICATION_FEE_VAT_RATE => $applicationFeeData?->applicationFeeVatRate,
            StripePaymentDomainObjectAbstract::CURRENCY => strtoupper($order->getCurrency()),
            StripePaymentDomainObjectAbstract::STRIPE_PLATFORM => $stripePlatform?->value,
        ]);

        return new CreatePaymentIntentResponseDTO(
            paymentIntentId: $paymentIntent->paymentIntentId,
            clientSecret: $paymentIntent->clientSecret,
            accountId: $paymentIntent->accountId,
            applicationFeeData: $paymentIntent->applicationFeeData,
            stripePlatform: $stripePlatform,
            publicKey: $publicKey,
        );
    }
}

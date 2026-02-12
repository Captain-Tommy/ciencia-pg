<?php

namespace Ciencia\Services\Domain\Payment\Stripe;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Exceptions\Stripe\StripeClientConfigurationException;
use Ciencia\Mail\Order\PaymentSuccessButOrderExpiredMail;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Services\Infrastructure\Stripe\StripeClientFactory;
use Ciencia\Values\MoneyValue;
use Illuminate\Contracts\Mail\Mailer;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

readonly class StripeRefundExpiredOrderService
{
    public function __construct(
        private StripePaymentIntentRefundService $refundService,
        private Mailer                           $mailer,
        private LoggerInterface                  $logger,
        private EventRepositoryInterface         $eventRepository,
        private StripeClientFactory              $stripeClientFactory,

    )
    {
    }

    /**
     * @throws ApiErrorException
     * @throws RoundingNecessaryException
     * @throws MathException
     * @throws UnknownCurrencyException
     * @throws NumberFormatException
     * @throws StripeClientConfigurationException
     */
    public function refundExpiredOrder(
        PaymentIntent             $paymentIntent,
        StripePaymentDomainObject $stripePayment,
        OrderDomainObject         $order,
    ): void
    {
        $event = $this->eventRepository
            ->loadRelation(new Relationship(EventSettingDomainObject::class))
            ->loadRelation(new Relationship(OrganizerDomainObject::class, name: 'organizer'))
            ->findById($order->getEventId());

        // Determine the correct Stripe platform for this refund
        // Use the platform that was used for the original payment
        $paymentPlatform = $stripePayment->getStripePlatformEnum();

        // Create Stripe client for the original payment's platform
        $stripeClient = $this->stripeClientFactory->createForPlatform($paymentPlatform);

        $this->refundService->refundPayment(
            MoneyValue::fromMinorUnit($paymentIntent->amount, strtoupper($paymentIntent->currency)),
            $stripePayment,
            $stripeClient
        );

        $this->mailer
            ->to($order->getEmail())
            ->locale($order->getLocale())
            ->send(new PaymentSuccessButOrderExpiredMail(
                order: $order,
                event: $event,
                eventSettings: $event->getEventSettings(),
                organizer: $event->getOrganizer(),
            ));

        $this->logger->info('Refunded expired order', [
            'order_id' => $order->getId(),
            'event_id' => $event->getId(),
            'payment_intent_id' => $paymentIntent->id,
        ]);
    }
}

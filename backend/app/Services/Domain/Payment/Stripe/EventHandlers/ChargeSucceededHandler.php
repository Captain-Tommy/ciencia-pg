<?php

namespace Ciencia\Services\Domain\Payment\Stripe\EventHandlers;

use Ciencia\DomainObjects\Generated\StripePaymentDomainObjectAbstract;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Repository\Eloquent\StripePaymentsRepository;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Services\Domain\Payment\Stripe\StripePaymentPlatformFeeExtractionService;
use Psr\Log\LoggerInterface;
use Stripe\Charge;

class ChargeSucceededHandler
{
    public function __construct(
        private readonly StripePaymentsRepository                  $stripePaymentsRepository,
        private readonly StripePaymentPlatformFeeExtractionService $platformFeeExtractionService,
        private readonly LoggerInterface                           $logger,
    )
    {
    }

    public function handleEvent(Charge $charge): void
    {
        $this->logger->info(__('Processing charge event'), [
            'charge_id' => $charge->id,
            'payment_intent_id' => $charge->payment_intent,
            'status' => $charge->status,
        ]);

        if ($charge->status !== 'succeeded') {
            $this->logger->info(__('Charge not in succeeded status, skipping'), [
                'charge_id' => $charge->id,
                'status' => $charge->status,
            ]);
            return;
        }

        /**@var StripePaymentDomainObject $stripePayment */
        $stripePayment = $this->stripePaymentsRepository
            ->loadRelation(new Relationship(OrderDomainObject::class, name: 'order'))
            ->findFirstWhere([
                StripePaymentDomainObjectAbstract::PAYMENT_INTENT_ID => $charge->payment_intent,
            ]);

        if (!$stripePayment) {
            $this->logger->warning(__('Stripe payment not found for charge'), [
                'charge_id' => $charge->id,
                'payment_intent_id' => $charge->payment_intent,
            ]);
            return;
        }

        $order = $stripePayment->getOrder();
        if (!$order) {
            $this->logger->warning(__('Order not found for charge'), [
                'charge_id' => $charge->id,
                'payment_intent_id' => $charge->payment_intent,
                'stripe_payment_id' => $stripePayment->getId(),
            ]);
            return;
        }

        $this->platformFeeExtractionService->extractAndStorePlatformFee(
            order: $order,
            charge: $charge,
            stripePayment: $stripePayment
        );
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Order\Payment\Stripe;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\Status\OrderRefundStatus;
use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Exceptions\RefundNotPossibleException;
use Ciencia\Mail\Order\OrderRefunded;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Application\Handlers\Order\DTO\RefundOrderDTO;
use Ciencia\Services\Domain\Order\OrderCancelService;
use Ciencia\Services\Domain\Payment\Stripe\StripePaymentIntentRefundService;
use Ciencia\Services\Infrastructure\Stripe\StripeClientFactory;
use Ciencia\Values\MoneyValue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\DatabaseManager;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class RefundOrderHandler
{
    public function __construct(
        private readonly StripePaymentIntentRefundService $refundService,
        private readonly OrderRepositoryInterface         $orderRepository,
        private readonly EventRepositoryInterface         $eventRepository,
        private readonly Mailer                           $mailer,
        private readonly OrderCancelService               $orderCancelService,
        private readonly DatabaseManager                  $databaseManager,
        private readonly StripeClientFactory              $stripeClientFactory,
    )
    {
    }

    /**
     * @throws RefundNotPossibleException
     * @throws ApiErrorException
     * @throws Throwable
     */
    public function handle(RefundOrderDTO $refundOrderDTO): OrderDomainObject
    {
        return $this->databaseManager->transaction(fn() => $this->refundOrder($refundOrderDTO));
    }

    private function fetchOrder(int $eventId, int $orderId): OrderDomainObject
    {
        $order = $this->orderRepository
            ->loadRelation(new Relationship(StripePaymentDomainObject::class, name: 'stripe_payment'))
            ->findFirstWhere(['event_id' => $eventId, 'id' => $orderId]);

        if (!$order) {
            throw new ResourceNotFoundException(__('Order :id not found for event :eventId', [
                'id' => $orderId,
                'eventId' => $eventId,
            ]));
        }

        return $order;
    }

    /**
     * @throws RefundNotPossibleException
     */
    private function validateRefundability(OrderDomainObject $order): void
    {
        if (!$order->getStripePayment()) {
            throw new RefundNotPossibleException(__('There is no Stripe data associated with this order.'));
        }

        if ($order->getRefundStatus() === OrderRefundStatus::REFUND_PENDING->name) {
            throw new RefundNotPossibleException(
                __('There is already a refund pending for this order.
                Please wait for the refund to be processed before requesting another one.')
            );
        }
    }

    private function notifyBuyer(OrderDomainObject $order, EventDomainObject $event, MoneyValue $amount): void
    {
        $this->mailer
            ->to($order->getEmail())
            ->locale($order->getLocale())
            ->send(new OrderRefunded(
                order: $order,
                event: $event,
                organizer: $event->getOrganizer(),
                eventSettings: $event->getEventSettings(),
                refundAmount: $amount
            ));
    }

    private function markOrderRefundPending(OrderDomainObject $order): OrderDomainObject
    {
        return $this->orderRepository->updateFromArray(
            id: $order->getId(),
            attributes: [
                OrderDomainObjectAbstract::REFUND_STATUS => OrderRefundStatus::REFUND_PENDING->name,
            ]
        );
    }

    /**
     * @throws ApiErrorException
     * @throws UnknownCurrencyException
     * @throws RefundNotPossibleException
     * @throws Throwable
     * @throws RoundingNecessaryException
     * @throws MathException
     * @throws NumberFormatException
     */
    private function refundOrder(RefundOrderDTO $refundOrderDTO): OrderDomainObject
    {
        $order = $this->fetchOrder($refundOrderDTO->event_id, $refundOrderDTO->order_id);
        $event = $this->eventRepository
            ->loadRelation(new Relationship(OrganizerDomainObject::class, name: 'organizer'))
            ->loadRelation(EventSettingDomainObject::class)
            ->findById($refundOrderDTO->event_id);

        $amount = MoneyValue::fromFloat($refundOrderDTO->amount, $order->getCurrency());

        $this->validateRefundability($order);

        if ($refundOrderDTO->cancel_order) {
            $this->orderCancelService->cancelOrder($order);
        }

        // Determine the correct Stripe platform for this refund
        // Use the platform that was used for the original payment
        $paymentPlatform = $order->getStripePayment()->getStripePlatformEnum();

        // Create Stripe client for the original payment's platform
        $stripeClient = $this->stripeClientFactory->createForPlatform($paymentPlatform);

        $this->refundService->refundPayment(
            amount: $amount,
            payment: $order->getStripePayment(),
            stripeClient: $stripeClient
        );

        if ($refundOrderDTO->notify_buyer) {
            $this->notifyBuyer($order, $event, $amount);
        }

        return $this->markOrderRefundPending($order);
    }
}

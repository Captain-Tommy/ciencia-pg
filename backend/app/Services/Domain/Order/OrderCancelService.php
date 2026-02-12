<?php

namespace Ciencia\Services\Domain\Order;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\Status\AttendeeStatus;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\Mail\Order\OrderCancelled;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Domain\Product\ProductQuantityUpdateService;
use Ciencia\Services\Infrastructure\DomainEvents\DomainEventDispatcherService;
use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;
use Ciencia\Services\Infrastructure\DomainEvents\Events\OrderEvent;
use Ciencia\Services\Domain\EventStatistics\EventStatisticsCancellationService;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\DatabaseManager;
use Throwable;

class OrderCancelService
{
    public function __construct(
        private readonly Mailer                              $mailer,
        private readonly AttendeeRepositoryInterface         $attendeeRepository,
        private readonly EventRepositoryInterface            $eventRepository,
        private readonly OrderRepositoryInterface            $orderRepository,
        private readonly DatabaseManager                     $databaseManager,
        private readonly ProductQuantityUpdateService        $productQuantityService,
        private readonly DomainEventDispatcherService        $domainEventDispatcherService,
        private readonly EventStatisticsCancellationService  $eventStatisticsCancellationService,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function cancelOrder(OrderDomainObject $order): void
    {
        $this->databaseManager->transaction(function () use ($order) {
            // Order of operations matters here. We must decrement the stats first.
            $this->eventStatisticsCancellationService->decrementForCancelledOrder($order);

            $this->adjustProductQuantities($order);
            $this->cancelAttendees($order);
            $this->updateOrderStatus($order);

            $event = $this->eventRepository
                ->loadRelation(new Relationship(OrganizerDomainObject::class, name: 'organizer'))
                ->loadRelation(EventSettingDomainObject::class)
                ->findById($order->getEventId());

            $this->mailer
                ->to($order->getEmail())
                ->locale($order->getLocale())
                ->send(new OrderCancelled(
                    order: $order,
                    event: $event,
                    organizer: $event->getOrganizer(),
                    eventSettings: $event->getEventSettings(),
                ));

            $this->domainEventDispatcherService->dispatch(
                new OrderEvent(
                    type: DomainEventType::ORDER_CANCELLED,
                    orderId: $order->getId(),
                ),
            );
        });
    }

    private function cancelAttendees(OrderDomainObject $order): void
    {
        $this->attendeeRepository->updateWhere(
            attributes: [
                'status' => AttendeeStatus::CANCELLED->name,
            ],
            where: [
                'order_id' => $order->getId(),
            ]
        );
    }

    private function adjustProductQuantities(OrderDomainObject $order): void
    {
        $attendees = $this->attendeeRepository->findWhere([
            'order_id' => $order->getId(),
        ])->filter(function (AttendeeDomainObject $attendee) use ($order) {
            if ($order->isOrderAwaitingOfflinePayment()) {
                return $attendee->getStatus() === AttendeeStatus::ACTIVE->name
                    || $attendee->getStatus() === AttendeeStatus::AWAITING_PAYMENT->name;
            }

            return $attendee->getStatus() === AttendeeStatus::ACTIVE->name;
        });

        $productIdCountMap = $attendees
            ->map(fn(AttendeeDomainObject $attendee) => $attendee->getProductPriceId())->countBy();

        foreach ($productIdCountMap as $productPriceId => $count) {
            $this->productQuantityService->decreaseQuantitySold($productPriceId, $count);
        }
    }

    private function updateOrderStatus(OrderDomainObject $order): void
    {
        $this->orderRepository->updateWhere(
            attributes: [
                'status' => OrderStatus::CANCELLED->name,
            ],
            where: [
                'id' => $order->getId(),
            ]
        );
    }
}

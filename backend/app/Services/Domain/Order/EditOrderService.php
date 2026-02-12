<?php

namespace Ciencia\Services\Domain\Order;

use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Infrastructure\DomainEvents\DomainEventDispatcherService;
use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;
use Ciencia\Services\Infrastructure\DomainEvents\Events\OrderEvent;
use Illuminate\Database\DatabaseManager;
use Throwable;

class EditOrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface     $orderRepository,
        private readonly DomainEventDispatcherService $domainEventDispatcherService,
        private readonly DatabaseManager              $databaseManager,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function editOrder(
        int     $id,
        int     $eventId,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $notes
    ): OrderDomainObject
    {
        return $this->databaseManager->transaction(function () use ($id, $firstName, $lastName, $email, $notes, $eventId) {
            $this->orderRepository->updateWhere(
                attributes: array_filter([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'notes' => $notes,
                ]),
                where: [
                    'id' => $id,
                    'event_id' => $eventId,
                ]
            );

            $this->domainEventDispatcherService->dispatch(
                new OrderEvent(
                    type: DomainEventType::ORDER_UPDATED,
                    orderId: $id,
                ),
            );

            return $this->orderRepository->findFirstWhere([
                'id' => $id,
                'event_id' => $eventId,
            ]);
        });
    }
}

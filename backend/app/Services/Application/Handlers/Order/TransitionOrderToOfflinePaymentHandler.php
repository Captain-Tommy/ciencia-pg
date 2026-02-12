<?php

namespace Ciencia\Services\Application\Handlers\Order;

use Ciencia\DomainObjects\Enums\PaymentProviders;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\Status\OrderPaymentStatus;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\Events\OrderStatusChangedEvent;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Exceptions\UnauthorizedException;
use Ciencia\Repository\Interfaces\EventSettingsRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Application\Handlers\Order\DTO\TransitionOrderToOfflinePaymentPublicDTO;
use Ciencia\Services\Domain\Product\ProductQuantityUpdateService;
use Ciencia\Services\Infrastructure\DomainEvents\DomainEventDispatcherService;
use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;
use Ciencia\Services\Infrastructure\DomainEvents\Events\OrderEvent;
use Illuminate\Database\DatabaseManager;

class TransitionOrderToOfflinePaymentHandler
{
    public function __construct(
        private readonly ProductQuantityUpdateService     $productQuantityUpdateService,
        private readonly OrderRepositoryInterface         $orderRepository,
        private readonly DatabaseManager                  $databaseManager,
        private readonly EventSettingsRepositoryInterface $eventSettingsRepository,
        private readonly DomainEventDispatcherService     $domainEventDispatcherService,

    )
    {
    }

    public function handle(TransitionOrderToOfflinePaymentPublicDTO $dto): OrderDomainObject
    {
        return $this->databaseManager->transaction(function () use ($dto) {
            /** @var OrderDomainObjectAbstract $order */
            $order = $this->orderRepository
                ->loadRelation(OrderItemDomainObject::class)
                ->findByShortId($dto->orderShortId);

            /** @var EventSettingDomainObject $eventSettings */
            $eventSettings = $this->eventSettingsRepository->findFirstWhere([
                'event_id' => $order->getEventId(),
            ]);

            $this->validateOfflinePayment($order, $eventSettings);

            $this->updateOrderStatuses($order->getId());

            $this->productQuantityUpdateService->updateQuantitiesFromOrder($order);

            $order = $this->orderRepository
                ->loadRelation(OrderItemDomainObject::class)
                ->findById($order->getId());

            event(new OrderStatusChangedEvent(
                order: $order,
                sendEmails: true,
                createInvoice: $eventSettings->getEnableInvoicing(),
            ));

            $this->domainEventDispatcherService->dispatch(
                new OrderEvent(
                    type: DomainEventType::ORDER_CREATED,
                    orderId: $order->getId(),
                ),
            );

            return $order;
        });
    }

    private function updateOrderStatuses(int $orderId): void
    {
        $this->orderRepository
            ->updateFromArray($orderId, [
                OrderDomainObjectAbstract::PAYMENT_STATUS => OrderPaymentStatus::AWAITING_OFFLINE_PAYMENT->name,
                OrderDomainObjectAbstract::STATUS => OrderStatus::AWAITING_OFFLINE_PAYMENT->name,
                OrderDomainObjectAbstract::PAYMENT_PROVIDER => PaymentProviders::OFFLINE->value,
            ]);
    }

    /**
     * @throws ResourceConflictException
     */
    public function validateOfflinePayment(
        OrderDomainObject        $order,
        EventSettingDomainObject $settings,
    ): void
    {
        if (!$order->isOrderReserved()) {
            throw new ResourceConflictException(__('Order is not in the correct status to transition to offline payment'));
        }

        if ($order->isReservedOrderExpired()) {
            throw new ResourceConflictException(__('Order reservation has expired'));
        }

        if (collect($settings->getPaymentProviders())->contains(PaymentProviders::OFFLINE->value) === false) {
            throw new UnauthorizedException(__('Offline payments are not enabled for this event'));
        }
    }
}

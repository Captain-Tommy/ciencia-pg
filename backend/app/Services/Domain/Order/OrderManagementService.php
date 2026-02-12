<?php

namespace Ciencia\Services\Domain\Order;

use Carbon\Carbon;
use Ciencia\DomainObjects\AffiliateDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\PromoCodeDomainObject;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\Helper\IdHelper;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Domain\Tax\TaxAndFeeOrderRollupService;
use Illuminate\Support\Collection;

class OrderManagementService
{
    public function __construct(
        readonly private OrderRepositoryInterface    $orderRepository,
        readonly private TaxAndFeeOrderRollupService $taxAndFeeOrderRollupService,
    )
    {
    }

    public function deleteExistingOrders(int $eventId, string $sessionId): void
    {
        $this->orderRepository->deleteWhere([
            OrderDomainObjectAbstract::SESSION_ID => $sessionId,
            OrderDomainObjectAbstract::STATUS => OrderStatus::RESERVED->name,
            OrderDomainObjectAbstract::EVENT_ID => $eventId,
        ]);
    }

    public function createNewOrder(
        int                    $eventId,
        EventDomainObject      $event,
        int                    $timeOutMinutes,
        string                 $locale,
        ?PromoCodeDomainObject $promoCode,
        ?AffiliateDomainObject $affiliate = null,
        string                 $sessionId = null,
    ): OrderDomainObject
    {
        $reservedUntil = Carbon::now()->addMinutes($timeOutMinutes);

        return $this->orderRepository->create([
            'event_id' => $eventId,
            'short_id' => IdHelper::shortId(IdHelper::ORDER_PREFIX),
            'reserved_until' => $reservedUntil->toString(),
            'status' => OrderStatus::RESERVED->name,
            'session_id' => $sessionId,
            'currency' => $event->getCurrency(),
            'public_id' => IdHelper::publicId(IdHelper::ORDER_PREFIX),
            'promo_code_id' => $promoCode?->getId(),
            'promo_code' => $promoCode?->getCode(),
            'affiliate_id' => $affiliate?->getId(),
            'locale' => $locale,
        ]);
    }

    /**
     * Update order totals by summing up all order items.
     * Platform fee and its tax are included at the item level.
     *
     * @param OrderDomainObject $order
     * @param Collection<OrderItemDomainObject> $orderItems
     * @return OrderDomainObject
     */
    public function updateOrderTotals(OrderDomainObject $order, Collection $orderItems): OrderDomainObject
    {
        $totalBeforeAdditions = 0;
        $totalTax = 0;
        $totalFee = 0;
        $totalGross = 0;

        foreach ($orderItems as $item) {
            $totalBeforeAdditions += $item->getTotalBeforeAdditions();
            $totalTax += $item->getTotalTax();
            $totalFee += $item->getTotalServiceFee();
            $totalGross += $item->getTotalGross();
        }

        $rollup = $this->taxAndFeeOrderRollupService->rollup($orderItems);

        $this->orderRepository->updateFromArray($order->getId(), [
            'total_before_additions' => $totalBeforeAdditions,
            'total_tax' => $totalTax,
            'total_fee' => $totalFee,
            'total_gross' => $totalGross,
            'taxes_and_fees_rollup' => $rollup,
        ]);

        return $this->orderRepository
            ->loadRelation(OrderItemDomainObject::class)
            ->findById($order->getId());
    }
}

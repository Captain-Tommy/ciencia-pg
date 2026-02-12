<?php

namespace Ciencia\Services\Application\Handlers\Order;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\EventDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\OrganizerDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\ProductDomainObjectAbstract;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\Exceptions\UnauthorizedException;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Application\Handlers\Order\DTO\GetOrderPublicDTO;
use Ciencia\Services\Infrastructure\Session\CheckoutSessionManagementService;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GetOrderPublicHandler
{
    public function __construct(
        private readonly OrderRepositoryInterface         $orderRepository,
        private readonly CheckoutSessionManagementService $sessionIdentifierService
    )
    {
    }

    public function handle(GetOrderPublicDTO $getOrderData): OrderDomainObject
    {
        $order = $this->getOrderDomainObject($getOrderData);

        if (!$order) {
            throw new ResourceNotFoundException(__('Order not found'));
        }

        if ($order->getStatus() === OrderStatus::RESERVED->name) {
            $this->verifySessionId($order->getSessionId());
        }

        return $order;
    }

    private function verifySessionId(string $orderSessionId): void
    {
        if (!$this->sessionIdentifierService->verifySession($orderSessionId)) {
            throw new UnauthorizedException(
                __('Sorry, we could not verify your session. Please restart your order.')
            );
        }
    }

    private function getOrderDomainObject(GetOrderPublicDTO $getOrderData): ?OrderDomainObject
    {
        $orderQuery = $this->orderRepository
            ->loadRelation(new Relationship(
                domainObject: AttendeeDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: ProductDomainObject::class,
                        nested: [
                            new Relationship(
                                domainObject: ProductPriceDomainObject::class,
                            )
                        ],
                        name: ProductDomainObjectAbstract::SINGULAR_NAME,
                    )
                ],
            ))
            ->loadRelation(new Relationship(domainObject: InvoiceDomainObject::class))
            ->loadRelation(new Relationship(
                domainObject: OrderItemDomainObject::class,
            ));

        if ($getOrderData->includeEventInResponse) {
            $orderQuery->loadRelation(new Relationship(
                domainObject: EventDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: EventSettingDomainObject::class,
                    ),
                    new Relationship(
                        domainObject: OrganizerDomainObject::class,
                        name: OrganizerDomainObjectAbstract::SINGULAR_NAME,
                    ),
                    new Relationship(
                        domainObject: ImageDomainObject::class,
                    )
                ],
                name: EventDomainObjectAbstract::SINGULAR_NAME
            ));
        }

        return $orderQuery->findByShortId($getOrderData->orderShortId);
    }
}

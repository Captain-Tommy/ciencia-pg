<?php

namespace Ciencia\Http\Actions\Orders;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\QuestionAndAnswerViewDomainObject;
use Ciencia\Exceptions\ResourceNotFoundException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\OrderAndDirection;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Resources\Order\OrderResource;
use Illuminate\Http\JsonResponse;

class GetOrderAction extends BaseAction
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(int $eventId, int $orderId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $order = $this->orderRepository
            ->loadRelation(OrderItemDomainObject::class)
            ->loadRelation(AttendeeDomainObject::class)
            ->loadRelation(new Relationship(domainObject: QuestionAndAnswerViewDomainObject::class, orderAndDirections: [
                new OrderAndDirection(order: 'question_id'),
            ]))
            ->findFirstWhere([
                OrderDomainObjectAbstract::ID => $orderId,
                OrderDomainObjectAbstract::EVENT_ID => $eventId,
            ]);

        if ($order === null) {
            throw new ResourceNotFoundException(__('Order not found'));
        }

        return $this->resourceResponse(OrderResource::class, $order);
    }
}

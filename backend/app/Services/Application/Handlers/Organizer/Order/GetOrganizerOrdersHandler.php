<?php

namespace Ciencia\Services\Application\Handlers\Organizer\Order;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetOrganizerOrdersHandler
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function handle(int $organizer, int $accountId, QueryParamsDTO $queryParams): LengthAwarePaginator
    {
        return $this->orderRepository
            ->loadRelation(OrderItemDomainObject::class)
            ->loadRelation(AttendeeDomainObject::class)
            ->loadRelation(InvoiceDomainObject::class)
            ->findByOrganizerId(
                organizerId: $organizer,
                accountId: $accountId,
                params: $queryParams,
            );
    }
}

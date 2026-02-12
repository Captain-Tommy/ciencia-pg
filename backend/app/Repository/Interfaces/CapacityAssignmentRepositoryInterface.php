<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends RepositoryInterface<CapacityAssignmentDomainObject>
 */
interface CapacityAssignmentRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator;
}

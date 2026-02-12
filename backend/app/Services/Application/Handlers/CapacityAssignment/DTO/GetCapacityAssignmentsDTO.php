<?php

namespace Ciencia\Services\Application\Handlers\CapacityAssignment\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Http\DTO\QueryParamsDTO;

class GetCapacityAssignmentsDTO extends BaseDTO
{
    public function __construct(
        public int            $eventId,
        public QueryParamsDTO $queryParams,
    )
    {
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\CheckInList\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Http\DTO\QueryParamsDTO;

class GetCheckInListsDTO extends BaseDTO
{
    public function __construct(
        public int            $eventId,
        public QueryParamsDTO $queryParams,
    )
    {
    }
}

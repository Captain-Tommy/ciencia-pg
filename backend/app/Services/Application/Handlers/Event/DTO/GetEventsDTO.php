<?php

namespace Ciencia\Services\Application\Handlers\Event\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Http\DTO\QueryParamsDTO;

class GetEventsDTO extends BaseDTO
{
    public function __construct(
        public int $accountId,
        public QueryParamsDTO $queryParams,
    )
    {
    }
}

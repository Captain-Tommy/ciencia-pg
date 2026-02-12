<?php

namespace Ciencia\Services\Application\Handlers\Event\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Http\DTO\QueryParamsDTO;

class GetPublicOrganizerEventsDTO extends BaseDTO
{
    public function __construct(
        public int            $organizerId,
        public QueryParamsDTO $queryParams,
        public ?int           $authenticatedAccountId = null,
    )
    {
    }
}

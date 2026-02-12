<?php

namespace Ciencia\Services\Application\Handlers\Organizer\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Http\DTO\QueryParamsDTO;

class GetOrganizerEventsDTO extends BaseDTO
{
    public function __construct(
        public int            $organizerId,
        public int            $accountId,
        public QueryParamsDTO $queryParams
    )
    {
    }
}

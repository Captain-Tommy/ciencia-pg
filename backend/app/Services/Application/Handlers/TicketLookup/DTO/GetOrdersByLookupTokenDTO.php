<?php

namespace Ciencia\Services\Application\Handlers\TicketLookup\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class GetOrdersByLookupTokenDTO extends BaseDataObject
{
    public function __construct(
        public readonly string $token,
    ) {
    }
}

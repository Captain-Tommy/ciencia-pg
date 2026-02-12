<?php

namespace Ciencia\Services\Application\Handlers\TicketLookup\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class SendTicketLookupEmailDTO extends BaseDataObject
{
    public function __construct(
        public readonly string $email,
    ) {
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class GetOrderPublicDTO extends BaseDTO
{
    public function __construct(
        public int    $eventId,
        public string $orderShortId,
        public bool   $includeEventInResponse = false,
    )
    {
    }
}

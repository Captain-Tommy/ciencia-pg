<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class CancelOrderDTO extends BaseDTO
{
    public function __construct(
        public int $eventId,
        public int $orderId,
        public bool $refund = false
    )
    {
    }
}

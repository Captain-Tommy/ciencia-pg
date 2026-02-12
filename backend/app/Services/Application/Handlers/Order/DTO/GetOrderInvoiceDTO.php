<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class GetOrderInvoiceDTO extends BaseDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $eventId,
    )
    {
    }
}

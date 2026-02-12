<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class MarkOrderAsPaidDTO extends BaseDTO
{
    public function __construct(
        public readonly int $eventId,
        public readonly int $orderId,
    )
    {
    }
}

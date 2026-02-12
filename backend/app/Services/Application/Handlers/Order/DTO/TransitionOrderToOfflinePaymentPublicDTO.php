<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class TransitionOrderToOfflinePaymentPublicDTO extends BaseDTO
{
    public function __construct(
        public readonly string $orderShortId,
    )
    {
    }
}

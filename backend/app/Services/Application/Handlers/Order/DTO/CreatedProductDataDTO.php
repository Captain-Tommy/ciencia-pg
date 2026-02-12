<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class CreatedProductDataDTO extends BaseDTO
{
    public function __construct(
        public readonly CompleteOrderProductDataDTO $productRequestData,
        public readonly ?string                      $shortId,
    )
    {
    }
}

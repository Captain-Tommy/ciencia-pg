<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\Attributes\CollectionOf;
use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\Services\Domain\Product\DTO\OrderProductPriceDTO;
use Illuminate\Support\Collection;

class ProductOrderDetailsDTO extends BaseDTO
{
    public function __construct(
        public readonly int $product_id,
        #[CollectionOf(OrderProductPriceDTO::class)]
        public Collection   $quantities,
    )
    {
    }
}

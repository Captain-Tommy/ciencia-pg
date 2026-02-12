<?php

namespace Ciencia\Services\Domain\Product\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Status\ProductStatus;

class ProductPriceDTO extends BaseDTO
{
    public function __construct(
        public readonly float         $price,
        public readonly ?string       $label = null,
        public readonly ?string       $sale_start_date = null,
        public readonly ?string       $sale_end_date = null,
        public readonly ?int          $initial_quantity_available = null,
        public readonly ?bool         $is_hidden = false,
        public readonly ?int          $id = null,
        public readonly ProductStatus $status = ProductStatus::ACTIVE,
    )
    {
    }
}

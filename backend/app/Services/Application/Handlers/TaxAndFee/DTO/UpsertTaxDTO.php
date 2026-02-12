<?php

namespace Ciencia\Services\Application\Handlers\TaxAndFee\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Enums\TaxCalculationType;
use Ciencia\DomainObjects\Enums\TaxType;

class UpsertTaxDTO extends BaseDTO
{
    public function __construct(
        public readonly string             $name,
        public readonly ?string            $description,
        public readonly TaxCalculationType $calculation_type,
        public readonly TaxType            $type,
        public readonly float              $rate,
        public readonly bool               $is_active,
        public readonly bool               $is_default,
        public readonly int                $account_id,
        public readonly ?int               $id = null,
    )
    {
    }
}

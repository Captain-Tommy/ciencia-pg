<?php

namespace Ciencia\Services\Application\Handlers\TaxAndFee\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class DeleteTaxDTO extends BaseDTO
{
    public function __construct(
        public readonly int $taxId,
        public readonly int $accountId,
    )
    {
    }
}

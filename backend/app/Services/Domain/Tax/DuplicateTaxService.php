<?php

namespace Ciencia\Services\Domain\Tax;

use Ciencia\Repository\Interfaces\TaxAndFeeRepositoryInterface;

class DuplicateTaxService
{
    private TaxAndFeeRepositoryInterface $taxRepository;

    public function __construct(TaxAndFeeRepositoryInterface $taxRepository)
    {
        $this->taxRepository = $taxRepository;
    }

    public function isDuplicate(string $name, int $accountId): bool
    {
        $existing = $this->taxRepository->findWhere([
            'name' => $name,
            'account_id' => $accountId,
        ]);

        return $existing->isNotEmpty();
    }
}

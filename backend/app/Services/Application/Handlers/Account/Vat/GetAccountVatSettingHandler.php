<?php

declare(strict_types=1);

namespace Ciencia\Services\Application\Handlers\Account\Vat;

use Ciencia\DomainObjects\AccountVatSettingDomainObject;
use Ciencia\Repository\Interfaces\AccountVatSettingRepositoryInterface;

class GetAccountVatSettingHandler
{
    public function __construct(
        private readonly AccountVatSettingRepositoryInterface $vatSettingRepository,
    ) {
    }

    public function handle(int $accountId): ?AccountVatSettingDomainObject
    {
        return $this->vatSettingRepository->findByAccountId($accountId);
    }
}

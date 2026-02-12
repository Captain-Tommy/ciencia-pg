<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\AccountVatSettingDomainObject;

interface AccountVatSettingRepositoryInterface extends RepositoryInterface
{
    public function findByAccountId(int $accountId): ?AccountVatSettingDomainObject;
}

<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\AccountConfigurationDomainObject;
use Ciencia\Models\AccountConfiguration;
use Ciencia\Repository\Interfaces\AccountConfigurationRepositoryInterface;

class AccountConfigurationRepository extends BaseRepository implements AccountConfigurationRepositoryInterface
{
    protected function getModel(): string
    {
        return AccountConfiguration::class;
    }

    public function getDomainObject(): string
    {
        return AccountConfigurationDomainObject::class;
    }
}

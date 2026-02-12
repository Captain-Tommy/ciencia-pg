<?php

declare(strict_types=1);

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\AccountStripePlatformDomainObject;
use Ciencia\Models\AccountStripePlatform;
use Ciencia\Repository\Interfaces\AccountStripePlatformRepositoryInterface;

class AccountStripePlatformRepository extends BaseRepository implements AccountStripePlatformRepositoryInterface
{
    protected function getModel(): string
    {
        return AccountStripePlatform::class;
    }

    public function getDomainObject(): string
    {
        return AccountStripePlatformDomainObject::class;
    }
}

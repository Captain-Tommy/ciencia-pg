<?php

declare(strict_types=1);

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\AccountMessagingTierDomainObject;
use Ciencia\Models\AccountMessagingTier;
use Ciencia\Repository\Interfaces\AccountMessagingTierRepositoryInterface;

class AccountMessagingTierRepository extends BaseRepository implements AccountMessagingTierRepositoryInterface
{
    protected function getModel(): string
    {
        return AccountMessagingTier::class;
    }

    public function getDomainObject(): string
    {
        return AccountMessagingTierDomainObject::class;
    }
}

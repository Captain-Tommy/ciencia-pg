<?php

declare(strict_types=1);

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\AccountUserDomainObject;
use Ciencia\Models\AccountUser;
use Ciencia\Repository\Interfaces\AccountUserRepositoryInterface;

class AccountUserRepository extends BaseRepository implements AccountUserRepositoryInterface
{
    protected function getModel(): string
    {
        return AccountUser::class;
    }

    public function getDomainObject(): string
    {
        return AccountUserDomainObject::class;
    }
}

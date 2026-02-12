<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\PasswordResetDomainObject;
use Ciencia\Models\PasswordReset;
use Ciencia\Repository\Interfaces\PasswordResetRepositoryInterface;

class PasswordResetRepository extends BaseRepository implements PasswordResetRepositoryInterface
{
    protected function getModel(): string
    {
        return PasswordReset::class;
    }

    public function getDomainObject(): string
    {
        return PasswordResetDomainObject::class;
    }
}

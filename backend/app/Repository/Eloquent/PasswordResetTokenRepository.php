<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\PasswordResetTokenDomainObject;
use Ciencia\Models\PasswordResetToken;
use Ciencia\Repository\Interfaces\PasswordResetTokenRepositoryInterface;

class PasswordResetTokenRepository extends BaseRepository implements PasswordResetTokenRepositoryInterface
{
    protected function getModel(): string
    {
        return PasswordResetToken::class;
    }

    public function getDomainObject(): string
    {
        return PasswordResetTokenDomainObject::class;
    }
}

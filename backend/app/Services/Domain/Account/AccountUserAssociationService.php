<?php

namespace Ciencia\Services\Domain\Account;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\DomainObjects\AccountUserDomainObject;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\Status\UserStatus;
use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Exceptions\UnauthorizedException;
use Ciencia\Repository\Interfaces\AccountUserRepositoryInterface;

readonly class AccountUserAssociationService
{
    public function __construct(
        private AccountUserRepositoryInterface $accountUserRepository,
    )
    {
    }

    public function associate(
        UserDomainObject    $user,
        AccountDomainObject $account,
        Role                $role,
        ?UserStatus         $status = null,
        ?int                $invitedByUserId = null,
        bool                $isAccountOwner = false,
    ): AccountUserDomainObject
    {
        if ($role === Role::SUPERADMIN) {
            throw new UnauthorizedException(__('Cannot associate a user with SUPERADMIN role to an account'));
        }

        $data = [
            'user_id' => $user->getId(),
            'account_id' => $account->getId(),
            'role' => $role->name,
            'is_account_owner' => $isAccountOwner,
        ];

        if ($status !== null) {
            $data['status'] = $status->name;
        }

        if ($invitedByUserId !== null) {
            $data['invited_by_user_id'] = $invitedByUserId;
        }

        return $this->accountUserRepository->create($data);
    }
}

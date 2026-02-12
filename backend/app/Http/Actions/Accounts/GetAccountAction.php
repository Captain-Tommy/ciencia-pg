<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Accounts;

use Ciencia\DomainObjects\AccountConfigurationDomainObject;
use Ciencia\DomainObjects\AccountStripePlatformDomainObject;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Resources\Account\AccountResource;
use Illuminate\Http\JsonResponse;

class GetAccountAction extends BaseAction
{
    protected AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(?int $accountId = null): JsonResponse
    {
        $this->minimumAllowedRole(Role::ORGANIZER);

        $account = $this->accountRepository
            ->loadRelation(new Relationship(
                domainObject: AccountConfigurationDomainObject::class,
                name: 'configuration',
            ))
            ->loadRelation(AccountStripePlatformDomainObject::class)
            ->findById($this->getAuthenticatedAccountId());

        return $this->resourceResponse(AccountResource::class, $account);
    }
}

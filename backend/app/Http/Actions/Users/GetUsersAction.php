<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\AccountUserDomainObject;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class GetUsersAction extends BaseAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(): JsonResponse
    {
        $this->minimumAllowedRole(Role::ADMIN);

        return $this->resourceResponse(
            UserResource::class,
            $this->userRepository
                ->loadRelation(new Relationship(domainObject: AccountUserDomainObject::class, name: 'currentAccountUser'))
                ->findUsersByAccountId($this->getAuthenticatedAccountId()),
        );
    }
}

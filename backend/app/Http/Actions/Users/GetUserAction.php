<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GetUserAction extends BaseAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $userId): JsonResponse
    {
        $this->minimumAllowedRole(Role::ADMIN);

        $user = $this->userRepository->findByIdAndAccountId($userId, $this->getAuthenticatedAccountId());

        if (!$user) {
            throw new ResourceNotFoundException();
        }

        return $this->resourceResponse(
            resource: UserResource::class,
            data: $user
        );
    }
}

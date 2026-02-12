<?php

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Exceptions\CannotUpdateResourceException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\User\UpdateUserRequest;
use Ciencia\Resources\User\UserResource;
use Ciencia\Services\Application\Handlers\User\DTO\UpdateUserDTO;
use Ciencia\Services\Application\Handlers\User\UpdateUserHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateUserAction extends BaseAction
{
    private UpdateUserHandler $updateUserHandler;

    public function __construct(UpdateUserHandler $updateUserHandler)
    {
        $this->updateUserHandler = $updateUserHandler;
    }

    /**
     * @throws ValidationException|Throwable
     */
    public function __invoke(UpdateUserRequest $request, int $userId): JsonResponse
    {
        $this->isActionAuthorized(
            entityId: $userId,
            entityType: UserDomainObject::class,
            minimumRole: Role::ADMIN
        );

        $authenticatedUser = $this->getAuthenticatedUser();

        $userData = $request->validated() + [
                'id' => $userId,
                'account_id' => $this->getAuthenticatedAccountId(),
                'updated_by_user_id' => $authenticatedUser->getId(),
            ];

        try {
            $user = $this->updateUserHandler->handle(UpdateUserDTO::fromArray($userData));
        } catch (CannotUpdateResourceException $e) {
            throw ValidationException::withMessages([
                'role' => $e->getMessage(),
            ]);
        }

        return $this->resourceResponse(UserResource::class, $user);
    }
}

<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\User\CreateUserRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\User\UserResource;
use Ciencia\Services\Application\Handlers\User\CreateUserHandler;
use Ciencia\Services\Application\Handlers\User\DTO\CreateUserDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateUserAction extends BaseAction
{
    public function __construct(
        private readonly CreateUserHandler $createUserHandler
    )
    {
    }

    /**
     * @throws ValidationException|Throwable
     */
    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $this->minimumAllowedRole(Role::ADMIN);

        $data = array_merge($request->validated(), [
            'invited_by' => $this->getAuthenticatedUser()->getId(),
            'account_id' => $this->getAuthenticatedAccountId(),
        ]);

        try {
            $user = $this->createUserHandler->handle(CreateUserDTO::from($data));
        } catch (ResourceConflictException $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }

        return $this->resourceResponse(
            resource: UserResource::class,
            data: $user,
            statusCode: ResponseCodes::HTTP_CREATED
        );
    }
}

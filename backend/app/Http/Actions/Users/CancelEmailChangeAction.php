<?php

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\User\UserResource;
use Ciencia\Services\Application\Handlers\User\CancelEmailChangeHandler;
use Ciencia\Services\Application\Handlers\User\DTO\CancelEmailChangeDTO;
use Illuminate\Http\JsonResponse;

class CancelEmailChangeAction extends BaseAction
{
    private CancelEmailChangeHandler $cancelEmailChangeHandler;

    public function __construct(CancelEmailChangeHandler $cancelEmailChangeHandler)
    {
        $this->cancelEmailChangeHandler = $cancelEmailChangeHandler;
    }

    public function __invoke(int $userId): JsonResponse
    {
        $this->isActionAuthorized($userId, UserDomainObject::class);

        $user = $this->cancelEmailChangeHandler->handle(
            new CancelEmailChangeDTO(
                userId: $userId,
                accountId: $this->getAuthenticatedAccountId(),
            )
        );

        return $this->resourceResponse(UserResource::class, $user);
    }
}

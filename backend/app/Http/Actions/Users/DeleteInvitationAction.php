<?php

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\Status\UserStatus;
use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DeleteInvitationAction extends BaseAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $userId): JsonResponse|Response
    {
        $this->isActionAuthorized($userId, UserDomainObject::class, Role::ADMIN);

        $user = $this->userRepository->findByIdAndAccountId($userId, $this->getAuthenticatedAccountId());

        if ($user->getCurrentAccountUser()?->getStatus() !== UserStatus::INVITED->name) {
            return $this->errorResponse(__('No invitation found for this user.'));
        }

        $this->userRepository->deleteWhere([
            'id' => $userId,
        ]);

        return $this->noContentResponse();
    }
}

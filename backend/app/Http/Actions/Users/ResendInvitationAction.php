<?php

namespace Ciencia\Http\Actions\Users;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\Status\UserStatus;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Services\Domain\User\SendUserInvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResendInvitationAction extends BaseAction
{
    private UserRepositoryInterface $userRepository;

    private SendUserInvitationService $invitationService;

    public function __construct(UserRepositoryInterface $userRepository, SendUserInvitationService $invitationService)
    {
        $this->userRepository = $userRepository;
        $this->invitationService = $invitationService;
    }

    public function __invoke(int $userId): JsonResponse|Response
    {
        $this->minimumAllowedRole(Role::ADMIN);

        $user = $this->userRepository->findByIdAndAccountId($userId, $this->getAuthenticatedAccountId());

        if ($user->getCurrentAccountUser()?->getStatus() !== UserStatus::INVITED->name) {
            return $this->errorResponse(__('User status is not Invited'));
        }

        $this->invitationService->sendInvitation($user, $this->getAuthenticatedAccountId());

        return $this->noContentResponse();
    }
}

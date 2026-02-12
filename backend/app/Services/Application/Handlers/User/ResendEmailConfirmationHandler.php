<?php

namespace Ciencia\Services\Application\Handlers\User;

use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Services\Domain\User\EmailConfirmationService;

class ResendEmailConfirmationHandler
{
    public function __construct(
        private readonly EmailConfirmationService $emailConfirmationService,
    )
    {
    }

    public function handle(UserDomainObject $user, int $accountId): void
    {
        $this->emailConfirmationService->sendConfirmation($user, $accountId);
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\User;

use Ciencia\Services\Application\Handlers\User\DTO\ConfirmEmailChangeDTO;
use Ciencia\Services\Domain\User\EmailConfirmationService;
use Ciencia\Services\Infrastructure\Encryption\Exception\DecryptionFailedException;
use Throwable;

readonly class ConfirmEmailAddressHandler
{
    public function __construct(
        private EmailConfirmationService $emailConfirmationService,
    )
    {
    }

    /**
     * @throws DecryptionFailedException|Throwable
     */
    public function handle(ConfirmEmailChangeDTO $data): void
    {
        $this->emailConfirmationService->confirmEmailAddress($data->token, $data->accountId);
    }
}

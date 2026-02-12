<?php

namespace Ciencia\Services\Application\Handlers\User;

use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Services\Application\Handlers\User\DTO\ConfirmEmailChangeDTO;
use Ciencia\Services\Infrastructure\Encryption\EncryptedPayloadService;
use Ciencia\Services\Infrastructure\Encryption\Exception\DecryptionFailedException;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class ConfirmEmailChangeHandler
{
    public function __construct(
        private LoggerInterface         $logger,
        private UserRepositoryInterface $userRepository,
        private EncryptedPayloadService $encryptedPayloadService,
        private DatabaseManager         $databaseManager,
    )
    {
    }

    /**
     * @throws DecryptionFailedException|Throwable
     */
    public function handle(ConfirmEmailChangeDTO $data): UserDomainObject
    {
        return $this->databaseManager->transaction(function () use ($data) {
            ['id' => $userId] = $this->encryptedPayloadService->decryptPayload($data->token);

            $user = $this->userRepository->findByIdAndAccountId($userId, $data->accountId);

            if ($user->getPendingEmail() === null) {
                throw new ResourceConflictException(__('No email change pending'));
            }

            $this->userRepository->updateWhere(
                attributes: [
                    'email' => $user->getPendingEmail(),
                    'pending_email' => null,
                ],
                where: [
                    'id' => $userId,
                ]
            );

            $this->logger->info('Confirming email change', [
                'user_id' => $userId,
                'old_email' => $user->getEmail(),
                'new_email' => $user->getPendingEmail(),
            ]);

            return $this->userRepository->findByIdAndAccountId($userId, $data->accountId);
        });
    }
}

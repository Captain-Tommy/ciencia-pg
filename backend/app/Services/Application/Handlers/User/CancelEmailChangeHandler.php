<?php

namespace Ciencia\Services\Application\Handlers\User;

use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Exceptions\ResourceNotFoundException;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Services\Application\Handlers\User\DTO\CancelEmailChangeDTO;
use Psr\Log\LoggerInterface;

class CancelEmailChangeHandler
{
    private LoggerInterface $logger;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        LoggerInterface         $logger,
        UserRepositoryInterface $userRepository,
    )
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    public function handle(CancelEmailChangeDTO $data): UserDomainObject
    {
        $user = $this->userRepository->findByIdAndAccountId($data->userId, $data->accountId);

        if ($user === null) {
            throw new ResourceNotFoundException(__('User not found'));
        }

        $this->userRepository->updateWhere(
            attributes: [
                'pending_email' => null,
            ],
            where: [
                'id' => $data->userId,
            ]
        );

        $this->logger->info('Cancelled email change', [
            'user_id' => $data->userId,
            'account_id' => $data->accountId,
        ]);

        return $this->userRepository->findByIdAndAccountId($data->userId, $data->accountId);
    }
}

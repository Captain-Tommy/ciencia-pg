<?php

namespace Ciencia\Services\Application\Handlers\Admin;

use Ciencia\Repository\Interfaces\AccountRepositoryInterface;

class GetAccountHandler
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function handle(int $accountId)
    {
        return $this->accountRepository->getAccountWithDetails($accountId);
    }
}

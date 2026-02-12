<?php

declare(strict_types=1);

namespace Ciencia\Services\Application\Handlers\Admin;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;

class UpdateAccountMessagingTierHandler
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function handle(int $accountId, int $tierId): AccountDomainObject
    {
        return $this->accountRepository->updateFromArray($accountId, [
            'account_messaging_tier_id' => $tierId,
        ]);
    }
}

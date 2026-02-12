<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\Models\Account;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<AccountDomainObject>
 */
interface AccountRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId): AccountDomainObject;

    public function getAllAccountsWithCounts(?string $search, int $perPage): LengthAwarePaginator;

    public function getAccountWithDetails(int $accountId): Account;
}

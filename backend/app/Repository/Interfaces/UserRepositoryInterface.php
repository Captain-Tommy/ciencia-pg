<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @extends BaseRepository<UserDomainObject>
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByIdAndAccountId(int $userId, int $accountId): UserDomainObject;

    public function findUsersByAccountId(int $accountId): ?Collection;

    public function getAllUsersWithAccounts(?string $search, int $perPage): LengthAwarePaginator;
}

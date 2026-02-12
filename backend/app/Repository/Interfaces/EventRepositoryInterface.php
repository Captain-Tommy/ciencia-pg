<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<EventDomainObject>
 */
interface EventRepositoryInterface extends RepositoryInterface
{
    public function findEventsForOrganizer(int $organizerId, int $accountId, QueryParamsDTO $params): LengthAwarePaginator;

    public function findEvents(array $where, QueryParamsDTO $params): LengthAwarePaginator;

    public function getUpcomingEventsForAdmin(int $perPage): LengthAwarePaginator;

    public function getAllEventsForAdmin(
        ?string $search = null,
        int $perPage = 20,
        ?string $sortBy = 'start_date',
        ?string $sortDirection = 'desc'
    ): LengthAwarePaginator;

    public function getSitemapEvents(int $page, int $perPage): LengthAwarePaginator;

    public function getSitemapEventCount(): int;
}

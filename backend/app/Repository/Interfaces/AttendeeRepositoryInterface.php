<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @extends BaseRepository<AttendeeDomainObject>
 */
interface AttendeeRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator;

    public function findByEventIdForExport(int $eventId): Collection;

    public function getAttendeesByCheckInShortId(string $shortId, QueryParamsDTO $params): Paginator;
}

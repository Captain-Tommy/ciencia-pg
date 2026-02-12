<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\DTO\CheckedInAttendeesCountDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @extends RepositoryInterface<CheckInListDomainObject>
 */
interface CheckInListRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator;

    public function getCheckedInAttendeeCountById(int $checkInListId): CheckedInAttendeesCountDTO;

    /**
     * @param array<int> $checkInListIds
     *
     * @return Collection<CheckedInAttendeesCountDTO>
     */
    public function getCheckedInAttendeeCountByIds(array $checkInListIds): Collection;
}

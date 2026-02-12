<?php

namespace Ciencia\Services\Application\Handlers\CheckInList;

use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\CheckInListRepositoryInterface;
use Ciencia\Services\Application\Handlers\CheckInList\DTO\GetCheckInListsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCheckInListsHandler
{
    public function __construct(
        private readonly CheckInListRepositoryInterface $checkInListRepository,
    )
    {
    }

    public function handle(GetCheckInListsDTO $dto): LengthAwarePaginator
    {
        $checkInLists = $this->checkInListRepository
            ->loadRelation(ProductDomainObject::class)
            ->loadRelation(new Relationship(domainObject: EventDomainObject::class, name: 'event'))
            ->findByEventId(
                eventId: $dto->eventId,
                params: $dto->queryParams,
            );

        if ($checkInLists->isEmpty()) {
            return $checkInLists;
        }

        $attendeeCheckInCounts = $this->checkInListRepository->getCheckedInAttendeeCountByIds(
            $checkInLists->map(fn($checkInList) => $checkInList->getId())->toArray(),
        );

        if ($attendeeCheckInCounts->isEmpty()) {
            return $checkInLists;
        }

        $checkInLists->each(function (CheckInListDomainObject $checkInList) use ($attendeeCheckInCounts) {
            $attendeeCheckInCount = $attendeeCheckInCounts->firstWhere('checkInListId', $checkInList->getId());

            $checkInList->setCheckedInCount($attendeeCheckInCount->checkedInCount ?? 0);
            $checkInList->setTotalAttendeesCount($attendeeCheckInCount->totalAttendeesCount ?? 0);
        });

        return $checkInLists;
    }
}

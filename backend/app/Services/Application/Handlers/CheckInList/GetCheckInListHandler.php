<?php

namespace Ciencia\Services\Application\Handlers\CheckInList;

use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\CheckInListRepositoryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GetCheckInListHandler
{
    public function __construct(
        private readonly CheckInListRepositoryInterface $checkInListRepository,
    )
    {
    }

    public function handle(int $checkInListId, int $eventId): CheckInListDomainObject
    {
        $checkInList = $this->checkInListRepository
            ->loadRelation(ProductDomainObject::class)
            ->loadRelation(new Relationship(domainObject: EventDomainObject::class, name: 'event'))
            ->findFirstWhere([
                'event_id' => $eventId,
                'id' => $checkInListId,
            ]);

        if ($checkInList === null) {
            throw new ResourceNotFoundException(__('Check-in list not found'));
        }

        $attendeeCheckInCount = $this->checkInListRepository->getCheckedInAttendeeCountById($checkInList->getId());

        $checkInList->setCheckedInCount($attendeeCheckInCount->checkedInCount ?? 0);
        $checkInList->setTotalAttendeesCount($attendeeCheckInCount->totalAttendeesCount ?? 0);

        return $checkInList;
    }
}

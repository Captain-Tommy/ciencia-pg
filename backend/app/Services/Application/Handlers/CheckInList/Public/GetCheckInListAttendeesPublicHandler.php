<?php

namespace Ciencia\Services\Application\Handlers\CheckInList\Public;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Generated\CheckInListDomainObjectAbstract;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\Exceptions\CannotCheckInException;
use Ciencia\Helper\DateHelper;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Repository\Interfaces\CheckInListRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GetCheckInListAttendeesPublicHandler
{
    public function __construct(
        private readonly AttendeeRepositoryInterface    $attendeeRepository,
        private readonly CheckInListRepositoryInterface $checkInListRepository,
    )
    {
    }

    /**
     * @throws CannotCheckInException
     */
    public function handle(string $shortId, QueryParamsDTO $queryParams): Paginator
    {
        $checkInList = $this->checkInListRepository
            ->loadRelation(ProductDomainObject::class)
            ->loadRelation(new Relationship(EventDomainObject::class, name: 'event'))
            ->findFirstWhere([
                CheckInListDomainObjectAbstract::SHORT_ID => $shortId,
            ]);

        if (!$checkInList) {
            throw new ResourceNotFoundException(__('Check-in list not found'));
        }

        $this->validateCheckInListIsActive($checkInList);

        $attendees = $this->attendeeRepository->getAttendeesByCheckInShortId($shortId, $queryParams);

        // Set the check-in for each attendee
        $attendees->getCollection()->transform(function (AttendeeDomainObject $attendee) use ($checkInList) {
            $attendee->setCheckIn($attendee->getCheckIns()?->first(fn ($checkIn) => $checkIn->getCheckInListId() === $checkInList->getId()));
            return $attendee;
        });

        return $attendees;
    }

    /**
     * @throws CannotCheckInException
     */
    private function validateCheckInListIsActive(CheckInListDomainObject $checkInList): void
    {
        if ($checkInList->getExpiresAt() && DateHelper::utcDateIsPast($checkInList->getExpiresAt())) {
            throw new CannotCheckInException(__('Check-in list has expired'));
        }

        if ($checkInList->getActivatesAt() && DateHelper::utcDateIsFuture($checkInList->getActivatesAt())) {
            throw new CannotCheckInException(__('Check-in list is not active yet'));
        }
    }
}

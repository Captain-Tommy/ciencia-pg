<?php

namespace Ciencia\Services\Application\Handlers\Attendee;

use Ciencia\DomainObjects\AttendeeCheckInDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAttendeesHandler
{
    public function __construct(
        private readonly AttendeeRepositoryInterface $attendeeRepository,
    )
    {
    }

    public function handle(int $eventId, QueryParamsDTO $queryParams): LengthAwarePaginator
    {
        return $this->attendeeRepository
            ->loadRelation(new Relationship(
                domainObject: OrderDomainObject::class,
                name: 'order'
            ))
            ->loadRelation(new Relationship(
                domainObject: AttendeeCheckInDomainObject::class,
                name: 'check_ins'
            ))
            ->findByEventId($eventId, $queryParams);
    }
}

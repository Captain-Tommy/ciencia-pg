<?php

namespace Ciencia\Http\Actions\Attendees;

use Ciencia\DomainObjects\AttendeeCheckInDomainObject;
use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\QuestionAndAnswerViewDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Resources\Attendee\AttendeeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetAttendeeAction extends BaseAction
{
    private AttendeeRepositoryInterface $attendeeRepository;

    public function __construct(AttendeeRepositoryInterface $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function __invoke(int $eventId, int $attendeeId): Response|JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $attendee = $this->attendeeRepository
            ->loadRelation(relationship: QuestionAndAnswerViewDomainObject::class)
            ->loadRelation(new Relationship(
                domainObject: ProductDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: ProductPriceDomainObject::class,
                    ),
                ], name: 'product'))
            ->loadRelation(new Relationship(
                domainObject: AttendeeCheckInDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: CheckInListDomainObject::class,
                        name: 'check_in_list',
                    ),
                ],
                name: 'check_ins'
            ))
            ->findFirstWhere([
                'id' => $attendeeId,
                'event_id' => $eventId,
            ]);

        if (!$attendee) {
            return $this->notFoundResponse();
        }

        return $this->resourceResponse(AttendeeResource::class, $attendee);
    }
}

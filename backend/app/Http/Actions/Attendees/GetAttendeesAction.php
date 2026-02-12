<?php

namespace Ciencia\Http\Actions\Attendees;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Resources\Attendee\AttendeeResource;
use Ciencia\Services\Application\Handlers\Attendee\GetAttendeesHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAttendeesAction extends BaseAction
{
    public function __construct(
        private readonly GetAttendeesHandler $getAttendeesHandler,
    )
    {
    }

    public function __invoke(int $eventId, Request $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $attendees = $this->getAttendeesHandler->handle(
            eventId: $eventId,
            queryParams: QueryParamsDTO::fromArray($request->query->all())
        );

        return $this->filterableResourceResponse(
            resource: AttendeeResource::class,
            data: $attendees,
            domainObject: AttendeeDomainObject::class,
        );
    }
}

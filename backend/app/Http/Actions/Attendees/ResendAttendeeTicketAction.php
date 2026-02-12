<?php

namespace Ciencia\Http\Actions\Attendees;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Attendee\DTO\ResendAttendeeTicketDTO;
use Ciencia\Services\Application\Handlers\Attendee\ResendAttendeeTicketHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ResendAttendeeTicketAction extends BaseAction
{
    public function __construct(
        private readonly ResendAttendeeTicketHandler $handler
    )
    {
    }

    public function __invoke(int $eventId, int $attendeeId): JsonResponse|Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->handler->handle(new ResendAttendeeTicketDTO(
                attendeeId: $attendeeId,
                eventId: $eventId
            ));

        } catch (ResourceNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->noContentResponse();
    }
}

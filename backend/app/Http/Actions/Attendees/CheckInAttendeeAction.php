<?php

namespace Ciencia\Http\Actions\Attendees;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\CannotCheckInException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Attendee\CheckInAttendeeRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Attendee\AttendeeResource;
use Ciencia\Services\Application\Handlers\Attendee\CheckInAttendeeHandler;
use Ciencia\Services\Application\Handlers\Attendee\DTO\CheckInAttendeeDTO;
use Illuminate\Http\JsonResponse;

class CheckInAttendeeAction extends BaseAction
{
    public function __construct(
        private readonly CheckInAttendeeHandler $checkInAttendeeHandler
    )
    {
    }

    public function __invoke(CheckInAttendeeRequest $request, int $eventId, string $attendeePublicId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $user = $this->getAuthenticatedUser();

        try {
            $attendee = $this->checkInAttendeeHandler->handle(CheckInAttendeeDTO::fromArray([
                'event_id' => $eventId,
                'attendee_public_id' => $attendeePublicId,
                'checked_in_by_user_id' => $user->getId(),
                'action' => $request->validated('action'),
            ]));
        } catch (CannotCheckInException $e) {
            return $this->errorResponse($e->getMessage(), ResponseCodes::HTTP_CONFLICT);
        }

        return $this->resourceResponse(AttendeeResource::class, $attendee);
    }
}

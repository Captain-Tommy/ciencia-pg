<?php

namespace Ciencia\Http\Actions\CheckInLists\Public;

use Ciencia\Exceptions\CannotCheckInException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Attendee\AttendeeWithCheckInPublicResource;
use Ciencia\Services\Application\Handlers\CheckInList\Public\GetCheckInListAttendeePublicHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCheckInListAttendeePublicAction extends BaseAction
{
    public function __construct(
        private readonly GetCheckInListAttendeePublicHandler $getCheckInListAttendeePublicHandler,
    )
    {
    }

    public function __invoke(string $shortId, string $attendeePublicId, Request $request): JsonResponse
    {
        try {
            $attendee = $this->getCheckInListAttendeePublicHandler->handle(
                shortId: $shortId,
                attendeePublicId: $attendeePublicId,
            );
        } catch (CannotCheckInException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: Response::HTTP_FORBIDDEN,
            );
        }

        return $this->resourceResponse(
            resource: AttendeeWithCheckInPublicResource::class,
            data: $attendee,
        );
    }
}

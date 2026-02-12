<?php

namespace Ciencia\Http\Actions\CheckInLists\Public;

use Ciencia\Exceptions\CannotCheckInException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\CheckInList\Public\DeleteAttendeeCheckInPublicHandler;
use Ciencia\Services\Application\Handlers\CheckInList\Public\DTO\DeleteAttendeeCheckInPublicDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteAttendeeCheckInPublicAction extends BaseAction
{
    public function __construct(
        private readonly DeleteAttendeeCheckInPublicHandler $deleteAttendeeCheckInPublicHandler,
    )
    {
    }

    public function __invoke(
        string  $checkInListShortId,
        string  $checkInShortId,
        Request $request
    ): Response|JsonResponse
    {
        try {
            $this->deleteAttendeeCheckInPublicHandler->handle(new DeleteAttendeeCheckInPublicDTO(
                checkInListShortId: $checkInListShortId,
                checkInShortId: $checkInShortId,
                checkInUserIpAddress: $request->ip(),
            ));
        } catch (CannotCheckInException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: Response::HTTP_CONFLICT
            );
        }

        return $this->deletedResponse();
    }
}

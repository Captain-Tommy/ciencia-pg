<?php

namespace Ciencia\Http\Actions\SelfService;

use Ciencia\Exceptions\SelfServiceDisabledException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\SelfService\DTO\ResendEmailPublicDTO;
use Ciencia\Services\Application\Handlers\SelfService\ResendAttendeeTicketPublicHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ResendAttendeeTicketPublicAction extends BaseAction
{
    public function __construct(
        private readonly ResendAttendeeTicketPublicHandler $handler
    ) {
    }

    public function __invoke(
        Request $request,
        int $eventId,
        string $orderShortId,
        string $attendeeShortId
    ): JsonResponse {
        try {
            $this->handler->handle(ResendEmailPublicDTO::from([
                'eventId' => $eventId,
                'orderShortId' => $orderShortId,
                'attendeeShortId' => $attendeeShortId,
                'ipAddress' => $this->getClientIp($request),
                'userAgent' => $request->userAgent(),
            ]));

            return $this->jsonResponse([
                'message' => __('Ticket resent successfully'),
            ]);
        } catch (SelfServiceDisabledException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (ResourceNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}

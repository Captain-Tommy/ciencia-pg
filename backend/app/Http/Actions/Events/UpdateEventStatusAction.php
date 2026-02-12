<?php

namespace Ciencia\Http\Actions\Events;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\AccountNotVerifiedException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Event\UpdateEventStatusRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Event\EventResource;
use Ciencia\Services\Application\Handlers\Event\DTO\UpdateEventStatusDTO;
use Ciencia\Services\Application\Handlers\Event\UpdateEventStatusHandler;
use Illuminate\Http\JsonResponse;

class UpdateEventStatusAction extends BaseAction
{
    public function __construct(
        private readonly UpdateEventStatusHandler $updateEventStatusHandler,
    )
    {
    }

    public function __invoke(UpdateEventStatusRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $updatedEvent = $this->updateEventStatusHandler->handle(UpdateEventStatusDTO::fromArray([
                'status' => $request->input('status'),
                'eventId' => $eventId,
                'accountId' => $this->getAuthenticatedAccountId(),
            ]));
        } catch (AccountNotVerifiedException $e) {
            return $this->errorResponse($e->getMessage(), ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->resourceResponse(EventResource::class, $updatedEvent);
    }
}

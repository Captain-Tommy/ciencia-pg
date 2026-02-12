<?php

namespace Ciencia\Http\Actions\Events;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\CannotChangeCurrencyException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Event\UpdateEventRequest;
use Ciencia\Resources\Event\EventResource;
use Ciencia\Services\Application\Handlers\Event\DTO\UpdateEventDTO;
use Ciencia\Services\Application\Handlers\Event\UpdateEventHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateEventAction extends BaseAction
{
    public function __construct(
        private readonly UpdateEventHandler $updateEventHandler
    )
    {
    }

    /**
     * @throws Throwable|ValidationException
     */
    public function __invoke(UpdateEventRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);
        $authorisedUser = $this->getAuthenticatedUser();

        try {
            $event = $this->updateEventHandler->handle(
                UpdateEventDTO::fromArray(
                    array_merge(
                        $request->validated(),
                        [
                            'id' => $eventId,
                            'account_id' => $this->getAuthenticatedAccountId(),
                            'user_id' => $authorisedUser->getId(),
                        ]
                    )
                )
            );
        } catch (CannotChangeCurrencyException $exception) {
            throw ValidationException::withMessages([
                'currency' => $exception->getMessage(),
            ]);
        }

        return $this->resourceResponse(EventResource::class, $event);
    }
}

<?php

namespace Ciencia\Http\Actions\EventSettings;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\EventSettings\UpdateEventSettingsRequest;
use Ciencia\Resources\Event\EventSettingsResource;
use Ciencia\Services\Application\Handlers\EventSettings\DTO\PartialUpdateEventSettingsDTO;
use Ciencia\Services\Application\Handlers\EventSettings\PartialUpdateEventSettingsHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class PartialEditEventSettingsAction extends BaseAction
{
    public function __construct(
        private readonly PartialUpdateEventSettingsHandler $partialUpdateEventSettingsHandler
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(UpdateEventSettingsRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $event = $this->partialUpdateEventSettingsHandler->handle(
            PartialUpdateEventSettingsDTO::fromArray([
                'settings' => $request->validated(),
                'event_id' => $eventId,
                'account_id' => $this->getAuthenticatedAccountId(),
            ]),
        );

        return $this->resourceResponse(EventSettingsResource::class, $event);
    }
}

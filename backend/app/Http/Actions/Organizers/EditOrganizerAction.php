<?php

namespace Ciencia\Http\Actions\Organizers;

use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Organizer\UpsertOrganizerRequest;
use Ciencia\Resources\Organizer\OrganizerResource;
use Ciencia\Services\Application\Handlers\Organizer\DTO\EditOrganizerDTO;
use Ciencia\Services\Application\Handlers\Organizer\EditOrganizerHandler;
use Illuminate\Http\JsonResponse;

class EditOrganizerAction extends BaseAction
{
    public function __construct(private readonly EditOrganizerHandler $editOrganizerHandler)
    {
    }

    public function __invoke(UpsertOrganizerRequest $request, int $organizerId): JsonResponse
    {
        $this->isActionAuthorized(
            entityId: $organizerId,
            entityType: OrganizerDomainObject::class,
        );

        $organizerData = array_merge(
            $request->validated(),
            [
                'id' => $organizerId,
                'account_id' => $this->getAuthenticatedAccountId(),
            ]
        );

        $organizer = $this->editOrganizerHandler->handle(
            organizerData: EditOrganizerDTO::from($organizerData),
        );

        return $this->resourceResponse(
            resource: OrganizerResource::class,
            data: $organizer,
        );
    }
}

<?php

namespace Ciencia\Http\Actions\Organizers;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Resources\Event\EventResource;
use Ciencia\Services\Application\Handlers\Organizer\DTO\GetOrganizerEventsDTO;
use Ciencia\Services\Application\Handlers\Organizer\GetOrganizerEventsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetOrganizerEventsAction extends BaseAction
{
    public function __construct(
        private readonly GetOrganizerEventsHandler $getOrganizerEventsHandler,
    )
    {
    }

    public function __invoke(int $organizerId, Request $request): JsonResponse
    {
        $this->isActionAuthorized(
            entityId: $organizerId,
            entityType: OrganizerDomainObject::class
        );

        $events = $this->getOrganizerEventsHandler->handle(new GetOrganizerEventsDTO(
            organizerId: $organizerId,
            accountId: $this->getAuthenticatedAccountId(),
            queryParams: QueryParamsDTO::fromArray($request->query->all())
        ));

        return $this->filterableResourceResponse(
            resource: EventResource::class,
            data: $events,
            domainObject: EventDomainObject::class
        );
    }
}

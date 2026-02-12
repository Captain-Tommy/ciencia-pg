<?php

namespace Ciencia\Http\Actions\CapacityAssignments;

use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\CapacityAssignment\CapacityAssignmentResource;
use Ciencia\Services\Application\Handlers\CapacityAssignment\DTO\GetCapacityAssignmentsDTO;
use Ciencia\Services\Application\Handlers\CapacityAssignment\GetCapacityAssignmentsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCapacityAssignmentsAction extends BaseAction
{
    public function __construct(
        private readonly GetCapacityAssignmentsHandler $getCapacityAssignmentsHandler,
    )
    {
    }

    public function __invoke(int $eventId, Request $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        return $this->filterableResourceResponse(
            resource: CapacityAssignmentResource::class,
            data: $this->getCapacityAssignmentsHandler->handle(
                GetCapacityAssignmentsDTO::fromArray([
                    'eventId' => $eventId,
                    'queryParams' => $this->getPaginationQueryParams($request),
                ]),
            ),
            domainObject: CapacityAssignmentDomainObject::class,
        );
    }
}

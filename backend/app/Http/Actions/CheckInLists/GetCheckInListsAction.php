<?php

namespace Ciencia\Http\Actions\CheckInLists;

use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\CheckInList\CheckInListResource;
use Ciencia\Services\Application\Handlers\CheckInList\DTO\GetCheckInListsDTO;
use Ciencia\Services\Application\Handlers\CheckInList\GetCheckInListsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCheckInListsAction extends BaseAction
{
    public function __construct(
        private readonly GetCheckInListsHandler $getCheckInListsHandler,
    )
    {
    }

    public function __invoke(int $eventId, Request $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        return $this->filterableResourceResponse(
            resource: CheckInListResource::class,
            data: $this->getCheckInListsHandler->handle(
                GetCheckInListsDTO::fromArray([
                    'eventId' => $eventId,
                    'queryParams' => $this->getPaginationQueryParams($request),
                ]),
            ),
            domainObject: CheckInListDomainObject::class,
        );
    }
}

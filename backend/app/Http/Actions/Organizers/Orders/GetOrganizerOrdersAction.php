<?php

namespace Ciencia\Http\Actions\Organizers\Orders;

use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Order\OrderResource;
use Ciencia\Services\Application\Handlers\Organizer\Order\GetOrganizerOrdersHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetOrganizerOrdersAction extends BaseAction
{
    public function __construct(
        private readonly GetOrganizerOrdersHandler $handler,
    )
    {
    }

    public function __invoke(Request $request, int $organizerId): JsonResponse
    {
        $this->isActionAuthorized($organizerId, OrganizerDomainObject::class);

        $orders = $this->handler->handle(
            organizer: $organizerId,
            accountId: $this->getAuthenticatedAccountId(),
            queryParams: $this->getPaginationQueryParams($request)
        );

        return $this->filterableResourceResponse(
            resource: OrderResource::class,
            data: $orders,
            domainObject: OrderDomainObject::class
        );
    }
}

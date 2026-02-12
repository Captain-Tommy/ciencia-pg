<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Events;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Event\EventResource;
use Ciencia\Services\Application\Handlers\Admin\DTO\GetUpcomingEventsDTO;
use Ciencia\Services\Application\Handlers\Admin\GetUpcomingEventsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUpcomingEventsAction extends BaseAction
{
    public function __construct(
        private readonly GetUpcomingEventsHandler $handler,
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $events = $this->handler->handle(new GetUpcomingEventsDTO(
            perPage: min((int)$request->query('per_page', 20), 100),
        ));

        return $this->resourceResponse(
            resource: EventResource::class,
            data: $events
        );
    }
}

<?php

namespace Ciencia\Http\Actions\CapacityAssignments;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\CapacityAssignment\DeleteCapacityAssignmentHandler;
use Illuminate\Http\Response;

class DeleteCapacityAssignmentAction extends BaseAction
{
    public function __construct(
        private readonly DeleteCapacityAssignmentHandler $deleteCapacityAssignmentHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $capacityAssignmentId): Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $this->deleteCapacityAssignmentHandler->handle(
            $capacityAssignmentId,
            $eventId,
        );

        return $this->noContentResponse();
    }
}

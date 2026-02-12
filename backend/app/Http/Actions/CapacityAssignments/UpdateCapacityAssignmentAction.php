<?php

namespace Ciencia\Http\Actions\CapacityAssignments;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\CapacityAssigment\UpsertCapacityAssignmentRequest;
use Ciencia\Resources\CapacityAssignment\CapacityAssignmentResource;
use Ciencia\Services\Application\Handlers\CapacityAssignment\DTO\UpsertCapacityAssignmentDTO;
use Ciencia\Services\Application\Handlers\CapacityAssignment\UpdateCapacityAssignmentHandler;
use Ciencia\Services\Domain\Product\Exception\UnrecognizedProductIdException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateCapacityAssignmentAction extends BaseAction
{
    public function __construct(
        private readonly UpdateCapacityAssignmentHandler $updateCapacityAssignmentHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $capacityAssignmentId, UpsertCapacityAssignmentRequest $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $assignment = $this->updateCapacityAssignmentHandler->handle(
                UpsertCapacityAssignmentDTO::fromArray([
                    'id' => $capacityAssignmentId,
                    'name' => $request->validated('name'),
                    'event_id' => $eventId,
                    'capacity' => $request->validated('capacity'),
                    'applies_to' => $request->validated('applies_to'),
                    'status' => $request->validated('status'),
                    'product_ids' => $request->validated('product_ids'),
                ]),
            );
        } catch (UnrecognizedProductIdException $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
                statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        return $this->resourceResponse(
            resource: CapacityAssignmentResource::class,
            data: $assignment,
        );
    }
}

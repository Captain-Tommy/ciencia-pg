<?php

namespace Ciencia\Services\Application\Handlers\CapacityAssignment;

use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Ciencia\DomainObjects\Enums\CapacityAssignmentAppliesTo;
use Ciencia\Services\Application\Handlers\CapacityAssignment\DTO\UpsertCapacityAssignmentDTO;
use Ciencia\Services\Domain\CapacityAssignment\UpdateCapacityAssignmentService;
use Ciencia\Services\Domain\Product\Exception\UnrecognizedProductIdException;

class UpdateCapacityAssignmentHandler
{
    public function __construct(
        private readonly UpdateCapacityAssignmentService $updateCapacityAssignmentService,
    )
    {
    }

    /**
     * @throws UnrecognizedProductIdException
     */
    public function handle(UpsertCapacityAssignmentDTO $data): CapacityAssignmentDomainObject
    {
        $capacityAssignment = (new CapacityAssignmentDomainObject)
            ->setId($data->id)
            ->setName($data->name)
            ->setEventId($data->event_id)
            ->setCapacity($data->capacity)
            ->setAppliesTo(CapacityAssignmentAppliesTo::PRODUCTS->name)
            ->setStatus($data->status->name);

        return $this->updateCapacityAssignmentService->updateCapacityAssignment(
            $capacityAssignment,
            $data->product_ids,
        );
    }
}

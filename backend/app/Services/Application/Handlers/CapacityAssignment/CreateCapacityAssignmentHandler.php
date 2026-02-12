<?php

namespace Ciencia\Services\Application\Handlers\CapacityAssignment;

use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Ciencia\DomainObjects\Enums\CapacityAssignmentAppliesTo;
use Ciencia\Services\Application\Handlers\CapacityAssignment\DTO\UpsertCapacityAssignmentDTO;
use Ciencia\Services\Domain\CapacityAssignment\CreateCapacityAssignmentService;
use Ciencia\Services\Domain\Product\Exception\UnrecognizedProductIdException;

class CreateCapacityAssignmentHandler
{
    public function __construct(
        private readonly CreateCapacityAssignmentService $createCapacityAssignmentService
    )
    {
    }

    /**
     * @throws UnrecognizedProductIdException
     */
    public function handle(UpsertCapacityAssignmentDTO $data): CapacityAssignmentDomainObject
    {
        $capacityAssignment = (new CapacityAssignmentDomainObject)
            ->setName($data->name)
            ->setEventId($data->event_id)
            ->setCapacity($data->capacity)
            ->setAppliesTo(CapacityAssignmentAppliesTo::PRODUCTS->name)
            ->setStatus($data->status->name);

        return $this->createCapacityAssignmentService->createCapacityAssignment(
            $capacityAssignment,
            $data->product_ids,
        );
    }
}

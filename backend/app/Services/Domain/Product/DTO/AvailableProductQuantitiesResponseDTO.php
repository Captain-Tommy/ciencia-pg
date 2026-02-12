<?php

namespace Ciencia\Services\Domain\Product\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Illuminate\Support\Collection;

class AvailableProductQuantitiesResponseDTO extends BaseDTO
{
    public function __construct(
        /** @var Collection<AvailableProductQuantitiesDTO> */
        public Collection  $productQuantities,
        /** @var Collection<CapacityAssignmentDomainObject> */
        public ?Collection $capacities = null,
    )
    {
    }
}

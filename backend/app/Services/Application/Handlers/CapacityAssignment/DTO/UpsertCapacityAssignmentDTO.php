<?php

namespace Ciencia\Services\Application\Handlers\CapacityAssignment\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Status\CapacityAssignmentStatus;

class UpsertCapacityAssignmentDTO extends BaseDTO
{
    public function __construct(
        public string                   $name,
        public int                      $event_id,
        public CapacityAssignmentStatus $status,

        public ?int                     $capacity,
        public ?array                   $product_ids = null,
        public ?int                     $id = null,
    )
    {
    }
}

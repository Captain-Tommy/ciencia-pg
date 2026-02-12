<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum CapacityAssignmentStatus
{
    use BaseEnum;

    case ACTIVE;
    case INACTIVE;
}

<?php

namespace Ciencia\DomainObjects\Enums;

enum CapacityAssignmentAppliesTo
{
    use BaseEnum;

    case PRODUCTS;
    case EVENT;
}

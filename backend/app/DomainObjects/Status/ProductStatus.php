<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum ProductStatus
{
    use BaseEnum;

    case ACTIVE;
    case INACTIVE;
}

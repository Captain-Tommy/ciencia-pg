<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum UserStatus
{
    use BaseEnum;

    case ACTIVE;
    case INVITED;
    case INACTIVE;
}

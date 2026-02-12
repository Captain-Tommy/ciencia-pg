<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum EventLifecycleStatus
{
    use BaseEnum;

    case UPCOMING;
    case ENDED;
    case ONGOING;
}

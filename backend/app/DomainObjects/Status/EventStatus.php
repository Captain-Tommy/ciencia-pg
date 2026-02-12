<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum EventStatus
{
    use BaseEnum;

    case DRAFT;
    case LIVE;
    case ARCHIVED;
}

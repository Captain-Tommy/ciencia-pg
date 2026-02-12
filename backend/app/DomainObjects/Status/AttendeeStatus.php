<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum AttendeeStatus
{
    use BaseEnum;

    case ACTIVE;
    case AWAITING_PAYMENT;
    case CANCELLED;
}

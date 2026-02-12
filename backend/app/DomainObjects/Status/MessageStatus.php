<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum MessageStatus
{
    use BaseEnum;

    case PENDING_REVIEW;
    case PROCESSING;
    case SENT;
    case FAILED;
    case SCHEDULED;
    case CANCELLED;
}

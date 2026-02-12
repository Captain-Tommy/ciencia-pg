<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum InvoiceStatus
{
    use BaseEnum;

    case UNPAID;
    case PAID;
    case VOID;
}

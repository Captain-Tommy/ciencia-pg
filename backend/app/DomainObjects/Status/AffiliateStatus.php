<?php

declare(strict_types=1);

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum AffiliateStatus: string
{
    use BaseEnum;

    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
}

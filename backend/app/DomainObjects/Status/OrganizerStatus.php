<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum OrganizerStatus: string
{
    use BaseEnum;

    case DRAFT = 'DRAFT';
    case LIVE = 'LIVE';
    case ARCHIVED = 'ARCHIVED';
}

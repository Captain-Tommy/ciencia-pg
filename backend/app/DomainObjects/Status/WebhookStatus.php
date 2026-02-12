<?php

namespace Ciencia\DomainObjects\Status;

use Ciencia\DomainObjects\Enums\BaseEnum;

enum WebhookStatus: string
{
    use BaseEnum;

    case ENABLED = 'ENABLED';
    case PAUSED = 'PAUSED';
}

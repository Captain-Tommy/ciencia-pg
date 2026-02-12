<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\WebhookDomainObject;
use Ciencia\Models\Webhook;
use Ciencia\Repository\Interfaces\WebhookRepositoryInterface;

class WebhookRepository extends BaseRepository implements WebhookRepositoryInterface
{
    protected function getModel(): string
    {
        return Webhook::class;
    }

    public function getDomainObject(): string
    {
        return WebhookDomainObject::class;
    }
}

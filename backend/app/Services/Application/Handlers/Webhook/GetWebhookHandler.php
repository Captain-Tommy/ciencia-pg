<?php

namespace Ciencia\Services\Application\Handlers\Webhook;

use Ciencia\DomainObjects\WebhookDomainObject;
use Ciencia\Repository\Interfaces\WebhookRepositoryInterface;

class GetWebhookHandler
{
    public function __construct(
        private readonly WebhookRepositoryInterface $webhookRepository,
    )
    {
    }

    public function handle(int $eventId, int $webhookId): WebhookDomainObject
    {
        return $this->webhookRepository->findFirstWhere(
            where: [
                'id' => $webhookId,
                'event_id' => $eventId,
            ]
        );
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Webhook;

use Ciencia\Repository\Eloquent\Value\OrderAndDirection;
use Ciencia\Repository\Interfaces\WebhookRepositoryInterface;
use Illuminate\Support\Collection;

class GetWebhooksHandler
{
    public function __construct(
        private readonly WebhookRepositoryInterface $webhookRepository,
    )
    {
    }

    public function handler(int $accountId, int $eventId): Collection
    {
        return $this->webhookRepository->findWhere(
            where: [
                'account_id' => $accountId,
                'event_id' => $eventId,
            ],
            orderAndDirections: [
                new OrderAndDirection('id', OrderAndDirection::DIRECTION_DESC),
            ]
        );
    }
}

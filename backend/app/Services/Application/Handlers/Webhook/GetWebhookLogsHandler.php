<?php

namespace Ciencia\Services\Application\Handlers\Webhook;

use Ciencia\Repository\Interfaces\WebhookLogRepositoryInterface;
use Ciencia\Repository\Interfaces\WebhookRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GetWebhookLogsHandler
{
    public function __construct(
        private readonly WebhookLogRepositoryInterface $webhookLogRepository,
        private readonly WebhookRepositoryInterface    $webhookRepository,
    )
    {
    }

    public function handle(int $eventId, int $webhookId): LengthAwarePaginator
    {
        $webhook = $this->webhookRepository->findFirstWhere(
            where: [
                'id' => $webhookId,
                'event_id' => $eventId,
            ]
        );

        if (!$webhook) {
            throw new ResourceNotFoundException(__('Webhook not found'));
        }

        return $this->webhookLogRepository
            ->paginateWhere(
                where: [
                    'webhook_id' => $webhook->getId(),
                ],
                limit: 10,
            );
    }
}

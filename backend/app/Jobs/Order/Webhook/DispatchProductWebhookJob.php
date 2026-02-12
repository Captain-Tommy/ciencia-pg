<?php

namespace Ciencia\Jobs\Order\Webhook;

use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;
use Ciencia\Services\Infrastructure\Webhook\WebhookDispatchService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchProductWebhookJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int             $productId,
        public DomainEventType $eventType,
    )
    {
    }

    public function handle(WebhookDispatchService $webhookDispatchService): void
    {
        $webhookDispatchService->dispatchProductWebhook(
            eventType: $this->eventType,
            productId: $this->productId,
        );
    }
}

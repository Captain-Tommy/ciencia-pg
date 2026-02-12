<?php

namespace Ciencia\Http\Actions\Webhooks;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Status\WebhookStatus;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Webhook\UpsertWebhookRequest;
use Ciencia\Resources\Webhook\WebhookResource;
use Ciencia\Services\Application\Handlers\Webhook\DTO\EditWebhookDTO;
use Ciencia\Services\Application\Handlers\Webhook\EditWebhookHandler;
use Illuminate\Http\JsonResponse;

class EditWebhookAction extends BaseAction
{
    public function __construct(
        private readonly EditWebhookHandler $editWebhookHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $webhookId, UpsertWebhookRequest $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $webhook = $this->editWebhookHandler->handle(
            new EditWebhookDTO(
                webhookId: $webhookId,
                url: $request->validated('url'),
                eventTypes: $request->validated('event_types'),
                eventId: $eventId,
                userId: $this->getAuthenticatedUser()->getId(),
                accountId: $this->getAuthenticatedAccountId(),
                status: WebhookStatus::fromName($request->validated('status')),
            )
        );

        return $this->resourceResponse(
            resource: WebhookResource::class,
            data: $webhook
        );
    }
}

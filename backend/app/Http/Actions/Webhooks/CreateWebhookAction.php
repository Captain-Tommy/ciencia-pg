<?php

namespace Ciencia\Http\Actions\Webhooks;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Status\WebhookStatus;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Webhook\UpsertWebhookRequest;
use Ciencia\Resources\Webhook\WebhookResource;
use Ciencia\Services\Application\Handlers\Webhook\CreateWebhookHandler;
use Ciencia\Services\Application\Handlers\Webhook\DTO\CreateWebhookDTO;
use Illuminate\Http\JsonResponse;

class CreateWebhookAction extends BaseAction
{
    public function __construct(
        private readonly CreateWebhookHandler $createWebhookHandler,
    )
    {
    }

    public function __invoke(int $eventId, UpsertWebhookRequest $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $webhook = $this->createWebhookHandler->handle(
            new CreateWebhookDTO(
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

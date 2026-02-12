<?php

namespace Ciencia\Http\Actions\Webhooks;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Webhook\DeleteWebhookHandler;
use Illuminate\Http\Response;

class DeleteWebhookAction extends BaseAction
{
    public function __construct(
        private readonly DeleteWebhookHandler $deleteWebhookHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $webhookId): Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $this->deleteWebhookHandler->handle(
            $eventId,
            $webhookId,
        );

        return $this->deletedResponse();
    }
}

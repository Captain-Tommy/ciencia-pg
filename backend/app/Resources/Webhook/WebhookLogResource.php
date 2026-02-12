<?php

namespace Ciencia\Resources\Webhook;

use Ciencia\DomainObjects\WebhookLogDomainObject;
use Ciencia\Resources\BaseResource;

/**
 * @mixin WebhookLogDomainObject
 */
class WebhookLogResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'webhook_id' => $this->getWebhookId(),
            'payload' => $this->getPayload(),
            'response_body' => $this->getResponseBody(),
            'response_code' => $this->getResponseCode(),
            'created_at' => $this->getCreatedAt(),
            'event_type' => $this->getEventType(),
        ];
    }
}

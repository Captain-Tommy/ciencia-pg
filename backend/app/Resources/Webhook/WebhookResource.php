<?php

namespace Ciencia\Resources\Webhook;

use Ciencia\DomainObjects\WebhookDomainObject;
use Ciencia\Resources\BaseResource;

/**
 * @mixin WebhookDomainObject
 */
class WebhookResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'event_types' => $this->getEventTypes(),
            'status' => $this->getStatus(),
            'last_triggered_at' => $this->getLastTriggeredAt(),
            'last_response_body' => $this->getLastResponseBody(),
            'last_response_code' => $this->getLastResponseCode(),
        ];
    }
}

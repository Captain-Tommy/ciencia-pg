<?php

declare(strict_types=1);

namespace Ciencia\Http\Resources\Admin;

use Ciencia\DomainObjects\AccountMessagingTierDomainObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AccountMessagingTierDomainObject
 */
class AccountMessagingTierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'max_messages_per_24h' => $this->getMaxMessagesPer24h(),
            'max_recipients_per_message' => $this->getMaxRecipientsPerMessage(),
            'links_allowed' => $this->getLinksAllowed(),
        ];
    }
}

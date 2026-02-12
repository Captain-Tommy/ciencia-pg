<?php

namespace Ciencia\Http\Request\Webhook;

use Ciencia\DomainObjects\Status\WebhookStatus;
use Ciencia\Http\Request\BaseRequest;
use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;
use Ciencia\Validators\Rules\NoInternalUrlRule;
use Illuminate\Validation\Rule;

class UpsertWebhookRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'url', new NoInternalUrlRule()],
            'event_types.*' => ['required', Rule::in(DomainEventType::valuesArray())],
            'status' => ['nullable', Rule::in(WebhookStatus::valuesArray())],
        ];
    }
}

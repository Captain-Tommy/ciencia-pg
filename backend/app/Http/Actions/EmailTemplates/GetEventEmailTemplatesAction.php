<?php

namespace Ciencia\Http\Actions\EmailTemplates;

use Ciencia\DomainObjects\Enums\EmailTemplateType;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Resources\EmailTemplateResource;
use Ciencia\Services\Application\Handlers\EmailTemplate\DTO\GetEmailTemplatesDTO;
use Ciencia\Services\Application\Handlers\EmailTemplate\GetEmailTemplatesHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class GetEventEmailTemplatesAction extends BaseAction
{
    public function __construct(
        private readonly GetEmailTemplatesHandler $handler
    )
    {
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $validated = $request->validate([
            'template_type' => ['nullable', new Enum(EmailTemplateType::class)],
            'include_inactive' => ['in:true,false'],
        ]);

        $templates = $this->handler->handle(
            new GetEmailTemplatesDTO(
                account_id: $this->getAuthenticatedAccountId(),
                organizer_id: null,
                event_id: $eventId,
                template_type: isset($validated['template_type'])
                    ? EmailTemplateType::from($validated['template_type'])
                    : null,
                include_inactive: $validated['include_inactive'] ?? false,
            )
        );

        return $this->resourceResponse(
            resource: EmailTemplateResource::class,
            data: $templates,
        );
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\EmailTemplate;

use Ciencia\Services\Application\Handlers\EmailTemplate\DTO\PreviewEmailTemplateDTO;
use Ciencia\Services\Domain\Email\EmailTemplateService;

class PreviewEmailTemplateHandler
{
    public function __construct(
        private readonly EmailTemplateService $emailTemplateService
    ) {
    }

    public function handle(PreviewEmailTemplateDTO $dto): array
    {
        return $this->emailTemplateService->previewTemplate(
            $dto->subject,
            $dto->body,
            $dto->template_type,
            $dto->cta
        );
    }
}
<?php

namespace Ciencia\Services\Application\Handlers\EmailTemplate;

use Ciencia\DomainObjects\Enums\EmailTemplateType;
use Ciencia\Services\Infrastructure\Email\LiquidTemplateRenderer;

class GetAvailableTokensHandler
{
    public function __construct(
        private readonly LiquidTemplateRenderer $liquidRenderer
    ) {
    }

    public function handle(EmailTemplateType $templateType): array
    {
        return $this->liquidRenderer->getAvailableTokens($templateType);
    }
}
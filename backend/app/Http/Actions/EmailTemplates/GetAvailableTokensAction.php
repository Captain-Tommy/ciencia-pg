<?php

namespace Ciencia\Http\Actions\EmailTemplates;

use Ciencia\DomainObjects\Enums\EmailTemplateType;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\ResponseCodes;
use Ciencia\Services\Application\Handlers\EmailTemplate\GetAvailableTokensHandler;
use Illuminate\Http\JsonResponse;

class GetAvailableTokensAction extends BaseAction
{
    public function __construct(
        private readonly GetAvailableTokensHandler $handler
    ) {
    }

    public function __invoke(string $templateType): JsonResponse
    {
        //no authorization needed

        $type = EmailTemplateType::tryFrom($templateType);

        if (!$type) {
            return $this->jsonResponse(['error' => __('Invalid template type')], ResponseCodes::HTTP_BAD_REQUEST);
        }

        $tokens = $this->handler->handle($type);

        return $this->jsonResponse(['tokens' => $tokens]);
    }
}

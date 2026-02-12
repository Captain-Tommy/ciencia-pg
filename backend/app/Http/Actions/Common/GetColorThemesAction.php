<?php

namespace Ciencia\Http\Actions\Common;

use Ciencia\DomainObjects\Enums\ColorTheme;
use Ciencia\Http\Actions\BaseAction;
use Illuminate\Http\JsonResponse;

class GetColorThemesAction extends BaseAction
{
    public function __invoke(): JsonResponse
    {
        return $this->jsonResponse(
            data: ColorTheme::getAllThemes(),
            wrapInData: true,
        );
    }
}

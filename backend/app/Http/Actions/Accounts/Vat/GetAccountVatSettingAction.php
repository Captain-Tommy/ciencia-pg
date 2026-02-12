<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Accounts\Vat;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Account\AccountVatSettingResource;
use Ciencia\Services\Application\Handlers\Account\Vat\GetAccountVatSettingHandler;
use Illuminate\Http\JsonResponse;

class GetAccountVatSettingAction extends BaseAction
{
    public function __construct(
        private readonly GetAccountVatSettingHandler $handler,
    ) {
    }

    public function __invoke(int $accountId): JsonResponse
    {
        $this->minimumAllowedRole(Role::ORGANIZER);

        if ($accountId !== $this->getAuthenticatedAccountId()) {
            return $this->errorResponse(__('Unauthorized'));
        }

        $vatSetting = $this->handler->handle($accountId);

        if (!$vatSetting) {
            return $this->jsonResponse(['data' => null]);
        }

        return $this->resourceResponse(AccountVatSettingResource::class, $vatSetting);
    }
}

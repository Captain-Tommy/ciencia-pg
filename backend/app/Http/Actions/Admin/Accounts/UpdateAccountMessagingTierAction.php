<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Accounts;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Account\AdminAccountDetailResource;
use Ciencia\Services\Application\Handlers\Admin\GetAccountHandler;
use Ciencia\Services\Application\Handlers\Admin\UpdateAccountMessagingTierHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateAccountMessagingTierAction extends BaseAction
{
    public function __construct(
        private readonly UpdateAccountMessagingTierHandler $handler,
        private readonly GetAccountHandler $getAccountHandler,
    ) {
    }

    public function __invoke(Request $request, int $accountId): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $validated = $request->validate([
            'messaging_tier_id' => 'required|integer|exists:account_messaging_tiers,id',
        ]);

        $this->handler->handle($accountId, $validated['messaging_tier_id']);

        $account = $this->getAccountHandler->handle($accountId);

        return $this->jsonResponse(new AdminAccountDetailResource($account), wrapInData: true);
    }
}

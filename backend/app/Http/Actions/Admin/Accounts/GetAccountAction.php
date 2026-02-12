<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Accounts;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Account\AdminAccountDetailResource;
use Ciencia\Services\Application\Handlers\Admin\GetAccountHandler;
use Illuminate\Http\JsonResponse;

class GetAccountAction extends BaseAction
{
    public function __construct(
        private readonly GetAccountHandler $handler,
    )
    {
    }

    public function __invoke(int $accountId): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $account = $this->handler->handle($accountId);

        return $this->jsonResponse(new AdminAccountDetailResource($account), wrapInData: true);
    }
}

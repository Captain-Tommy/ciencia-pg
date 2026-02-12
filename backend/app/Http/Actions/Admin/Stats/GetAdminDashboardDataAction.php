<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Stats;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Admin\DTO\GetAdminDashboardDataDTO;
use Ciencia\Services\Application\Handlers\Admin\GetAdminDashboardDataHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAdminDashboardDataAction extends BaseAction
{
    public function __construct(
        private readonly GetAdminDashboardDataHandler $handler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $data = $this->handler->handle(new GetAdminDashboardDataDTO(
            days: min((int)$request->query('days', 14), 90),
            limit: min((int)$request->query('limit', 10), 50),
        ));

        return $this->jsonResponse($data->toArray());
    }
}

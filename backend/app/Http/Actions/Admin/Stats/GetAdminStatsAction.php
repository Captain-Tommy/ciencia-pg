<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Stats;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Admin\GetAdminStatsHandler;
use Illuminate\Http\JsonResponse;

class GetAdminStatsAction extends BaseAction
{
    public function __construct(
        private readonly GetAdminStatsHandler $handler,
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $stats = $this->handler->handle();

        return $this->jsonResponse($stats->toArray());
    }
}

<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Configurations;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\AccountConfigurationRepositoryInterface;
use Ciencia\Resources\Account\AccountConfigurationResource;
use Illuminate\Http\JsonResponse;

class GetAllConfigurationsAction extends BaseAction
{
    public function __construct(
        private readonly AccountConfigurationRepositoryInterface $repository,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $configurations = $this->repository->all();

        return $this->jsonResponse(
            AccountConfigurationResource::collection($configurations),
            wrapInData: true
        );
    }
}

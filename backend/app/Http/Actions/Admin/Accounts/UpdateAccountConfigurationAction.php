<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\Accounts;

use Ciencia\DataTransferObjects\UpdateAccountConfigurationDTO;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Account\AccountConfigurationResource;
use Ciencia\Services\Application\Handlers\Admin\UpdateAccountConfigurationHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateAccountConfigurationAction extends BaseAction
{
    public function __construct(
        private readonly UpdateAccountConfigurationHandler $handler,
    )
    {
    }

    public function __invoke(Request $request, int $accountId): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $validated = $request->validate([
            'application_fees' => 'required|array',
            'application_fees.fixed' => 'required|numeric|min:0',
            'application_fees.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $configuration = $this->handler->handle(new UpdateAccountConfigurationDTO(
            accountId: $accountId,
            applicationFees: $validated['application_fees'],
        ));

        return $this->resourceResponse(
            resource: AccountConfigurationResource::class,
            data: $configuration
        );
    }
}

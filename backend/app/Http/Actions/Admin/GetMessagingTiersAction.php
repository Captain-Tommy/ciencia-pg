<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Resources\Admin\AccountMessagingTierResource;
use Ciencia\Repository\Interfaces\AccountMessagingTierRepositoryInterface;
use Illuminate\Http\JsonResponse;

class GetMessagingTiersAction extends BaseAction
{
    public function __construct(
        private readonly AccountMessagingTierRepositoryInterface $messagingTierRepository,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $tiers = $this->messagingTierRepository->all();

        return $this->resourceResponse(
            resource: AccountMessagingTierResource::class,
            data: $tiers
        );
    }
}

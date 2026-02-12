<?php

namespace Ciencia\Http\Actions\Accounts;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Account\UpdateAccountRequest;
use Ciencia\Resources\Account\AccountResource;
use Ciencia\Services\Application\Handlers\Account\DTO\UpdateAccountDTO;
use Ciencia\Services\Application\Handlers\Account\UpdateAccountHanlder;
use Illuminate\Http\JsonResponse;

class UpdateAccountAction extends BaseAction
{
    private UpdateAccountHanlder $updateAccountHandler;

    public function __construct(UpdateAccountHanlder $updateAccountHandler)
    {
        $this->updateAccountHandler = $updateAccountHandler;
    }

    public function __invoke(UpdateAccountRequest $request): JsonResponse
    {
        $this->minimumAllowedRole(Role::ADMIN);

        $authUser = $this->getAuthenticatedUser();

        $payload = array_merge($request->validated(), [
            'account_id' => $this->getAuthenticatedAccountId(),
            'updated_by_user_id' => $authUser->getId(),
        ]);

        $account = $this->updateAccountHandler->handle(UpdateAccountDTO::fromArray($payload));

        return $this->resourceResponse(AccountResource::class, $account);
    }
}

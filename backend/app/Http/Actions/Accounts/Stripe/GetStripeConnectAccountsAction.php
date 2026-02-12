<?php

namespace Ciencia\Http\Actions\Accounts\Stripe;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Account\Stripe\StripeConnectAccountsResponseResource;
use Ciencia\Services\Application\Handlers\Account\Payment\Stripe\GetStripeConnectAccountsHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class GetStripeConnectAccountsAction extends BaseAction
{
    public function __construct(
        private readonly GetStripeConnectAccountsHandler $getStripeConnectAccountsHandler,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(int $accountId): JsonResponse
    {
        $this->isActionAuthorized($accountId, AccountDomainObject::class, Role::ADMIN);

        $result = $this->getStripeConnectAccountsHandler->handle($accountId);

        return $this->resourceResponse(
            resource: StripeConnectAccountsResponseResource::class,
            data: $result,
        );
    }
}

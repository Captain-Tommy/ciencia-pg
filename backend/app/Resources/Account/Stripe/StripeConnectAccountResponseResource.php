<?php

namespace Ciencia\Resources\Account\Stripe;

use Ciencia\Resources\Account\AccountResource;
use Ciencia\Services\Application\Handlers\Account\Payment\Stripe\DTO\CreateStripeConnectAccountResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CreateStripeConnectAccountResponse
 */
class StripeConnectAccountResponseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'stripe_connect_account_type' => $this->stripeConnectAccountType,
            'stripe_account_id' => $this->stripeAccountId,
            'is_connect_setup_complete' => $this->isConnectSetupComplete,
            'connect_url' => $this->connectUrl,
            'account' => new AccountResource($this->account),
        ];
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Account\Payment\Stripe\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\AccountDomainObject;
use Illuminate\Support\Collection;

class GetStripeConnectAccountsResponseDTO extends BaseDataObject
{
    public function __construct(
        public readonly AccountDomainObject $account,
        public readonly Collection $stripeConnectAccounts,
        public readonly ?string $primaryStripeAccountId = null,
        public readonly bool $hasCompletedSetup = false,
    ) {
    }
}
<?php

namespace Ciencia\Services\Application\Handlers\Account\Payment\Stripe\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\Enums\StripePlatform;

class CreateStripeConnectAccountDTO extends BaseDataObject
{
    public function __construct(
        public readonly int                 $accountId,
        public readonly StripePlatform|null $platform = null,
    )
    {
    }
}

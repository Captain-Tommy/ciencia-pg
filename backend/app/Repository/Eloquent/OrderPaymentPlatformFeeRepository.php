<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrderPaymentPlatformFeeDomainObject;
use Ciencia\Models\OrderPaymentPlatformFee;
use Ciencia\Repository\Interfaces\OrderPaymentPlatformFeeRepositoryInterface;

class OrderPaymentPlatformFeeRepository extends BaseRepository implements OrderPaymentPlatformFeeRepositoryInterface
{
    protected function getModel(): string
    {
        return OrderPaymentPlatformFee::class;
    }

    public function getDomainObject(): string
    {
        return OrderPaymentPlatformFeeDomainObject::class;
    }
}

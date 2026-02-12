<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrderRefundDomainObject;
use Ciencia\Models\OrderRefund;
use Ciencia\Repository\Interfaces\OrderRefundRepositoryInterface;

class OrderRefundRepository extends BaseRepository implements OrderRefundRepositoryInterface
{
    protected function getModel(): string
    {
        return OrderRefund::class;
    }

    public function getDomainObject(): string
    {
        return OrderRefundDomainObject::class;
    }
}

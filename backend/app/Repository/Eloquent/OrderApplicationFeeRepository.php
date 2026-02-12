<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrderApplicationFeeDomainObject;
use Ciencia\Models\OrderApplicationFee;
use Ciencia\Repository\Interfaces\OrderApplicationFeeRepositoryInterface;

class OrderApplicationFeeRepository extends BaseRepository implements OrderApplicationFeeRepositoryInterface
{
    protected function getModel(): string
    {
        return OrderApplicationFee::class;
    }

    public function getDomainObject(): string
    {
        return OrderApplicationFeeDomainObject::class;
    }
}

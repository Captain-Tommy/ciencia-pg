<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\Models\TaxAndFee;
use Ciencia\Repository\Interfaces\TaxAndFeeRepositoryInterface;

class TaxAndFeeRepository extends BaseRepository implements TaxAndFeeRepositoryInterface
{
    public function getDomainObject(): string
    {
        return TaxAndFeesDomainObject::class;
    }

    protected function getModel(): string
    {
        return TaxAndFee::class;
    }
}

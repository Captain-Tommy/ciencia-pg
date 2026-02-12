<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\Models\ProductPrice;
use Ciencia\Repository\Interfaces\ProductPriceRepositoryInterface;

class ProductPriceRepository extends BaseRepository implements ProductPriceRepositoryInterface
{
    protected function getModel(): string
    {
        return ProductPrice::class;
    }

    public function getDomainObject(): string
    {
        return ProductPriceDomainObject::class;
    }
}

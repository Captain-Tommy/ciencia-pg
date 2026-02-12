<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\Models\OrderItem;
use Ciencia\Repository\Interfaces\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    protected function getModel(): string
    {
        return OrderItem::class;
    }

    public function getDomainObject(): string
    {
        return OrderItemDomainObject::class;
    }
}

<?php

namespace Ciencia\Services\Infrastructure\DomainEvents\Events;

use Ciencia\Services\Infrastructure\DomainEvents\Enums\DomainEventType;

class ProductEvent extends BaseDomainEvent
{
    public function __construct(
        public DomainEventType $type,
        public int $productId,
    )
    {
    }
}

<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\StripeCustomerDomainObject;
use Ciencia\Models\StripeCustomer;
use Ciencia\Repository\Interfaces\StripeCustomerRepositoryInterface;

class StripeCustomerRepository extends BaseRepository implements StripeCustomerRepositoryInterface
{
    protected function getModel(): string
    {
        return StripeCustomer::class;
    }

    public function getDomainObject(): string
    {
        return StripeCustomerDomainObject::class;
    }
}

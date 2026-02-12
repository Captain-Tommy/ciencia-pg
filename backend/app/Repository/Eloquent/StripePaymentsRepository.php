<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Models\StripePayment;
use Ciencia\Repository\Interfaces\StripePaymentsRepositoryInterface;

class StripePaymentsRepository extends BaseRepository implements StripePaymentsRepositoryInterface
{
    protected function getModel(): string
    {
        return StripePayment::class;
    }

    public function getDomainObject(): string
    {
        return StripePaymentDomainObject::class;
    }
}

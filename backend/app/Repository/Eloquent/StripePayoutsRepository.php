<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\Repository\Interfaces\StripePayoutsRepositoryInterface;
use Ciencia\Models\StripePayout;
use Ciencia\DomainObjects\StripePayoutDomainObject;

class StripePayoutsRepository extends BaseRepository implements StripePayoutsRepositoryInterface
{
    protected function getModel(): string
    {
        return StripePayout::class;
    }

    public function getDomainObject(): string
    {
        return StripePayoutDomainObject::class;
    }
}

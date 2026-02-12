<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\StripePaymentDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<StripePaymentDomainObject>
 */
interface StripePaymentsRepositoryInterface extends RepositoryInterface
{
}

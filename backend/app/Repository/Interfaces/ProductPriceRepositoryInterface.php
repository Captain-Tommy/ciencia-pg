<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<ProductPriceDomainObject>
 */
interface ProductPriceRepositoryInterface extends RepositoryInterface
{
}

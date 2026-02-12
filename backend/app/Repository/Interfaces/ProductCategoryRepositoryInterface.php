<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\ProductCategoryDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;

/**
 * @extends BaseRepository<ProductCategoryDomainObject>
 */
interface ProductCategoryRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $queryParamsDTO): Collection;

    public function getNextOrder(int $eventId);
}

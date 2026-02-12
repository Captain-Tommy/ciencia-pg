<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\PromoCodeDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<PromoCodeDomainObject>
 */
interface PromoCodeRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator;
}

<?php

namespace Ciencia\Services\Application\Handlers\ProductCategory;

use Ciencia\DomainObjects\Generated\ProductCategoryDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\ProductDomainObjectAbstract;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\Repository\Eloquent\Value\OrderAndDirection;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Support\Collection;

class GetProductCategoriesHandler
{
    public function __construct(
        private readonly ProductCategoryRepositoryInterface $productCategoryRepository,
    )
    {
    }

    public function handle(int $eventId): Collection
    {
        return $this->productCategoryRepository
            ->loadRelation(new Relationship(
                domainObject: ProductDomainObject::class,
                nested: [
                    new Relationship(ProductPriceDomainObject::class),
                    new Relationship(TaxAndFeesDomainObject::class),
                ],
                orderAndDirections: [
                    new OrderAndDirection(
                        order: ProductDomainObjectAbstract::ORDER,
                    ),
                ],
            ))
            ->findWhere(
                where: [
                    'event_id' => $eventId,
                ],
                orderAndDirections: [
                    new OrderAndDirection(
                        order: ProductCategoryDomainObjectAbstract::ORDER,
                    ),
                ],
            );
    }
}

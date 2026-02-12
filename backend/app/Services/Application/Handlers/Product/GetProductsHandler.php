<?php

namespace Ciencia\Services\Application\Handlers\Product;

use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Interfaces\ProductRepositoryInterface;
use Ciencia\Services\Domain\Product\ProductFilterService;
use Illuminate\Pagination\LengthAwarePaginator;

class GetProductsHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductFilterService       $productFilterService,
    )
    {
    }

    public function handle(int $eventId, QueryParamsDTO $queryParamsDTO): LengthAwarePaginator
    {
        $productPaginator = $this->productRepository
            ->loadRelation(ProductPriceDomainObject::class)
            ->loadRelation(TaxAndFeesDomainObject::class)
            ->findByEventId($eventId, $queryParamsDTO);

        $filteredProducts = $this->productFilterService->filter(
            productsCategories: $productPaginator->getCollection(),
            hideSoldOutProducts: false,
        );

        $productPaginator->setCollection($filteredProducts);

        return $productPaginator;
    }
}

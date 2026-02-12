<?php

namespace Ciencia\Services\Domain\Product;

use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\Repository\Interfaces\ProductRepositoryInterface;

class ProductOrderingService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    )
    {
    }

    public function getOrderForNewProduct(int $eventId, int $productCategoryId): int
    {
        return ($this->productRepository->findWhere([
                'event_id' => $eventId,
                'product_category_id' => $productCategoryId,
            ])
                ->max((static fn(ProductDomainObject $product) => $product->getOrder())) ?? 0) + 1;
    }
}

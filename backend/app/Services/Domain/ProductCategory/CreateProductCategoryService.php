<?php

namespace Ciencia\Services\Domain\ProductCategory;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ProductCategoryDomainObject;
use Ciencia\Repository\Interfaces\ProductCategoryRepositoryInterface;

class CreateProductCategoryService
{
    public function __construct(
        private readonly ProductCategoryRepositoryInterface $productCategoryRepository,
    )
    {
    }

    public function createCategory(ProductCategoryDomainObject $productCategoryDomainObject): ProductCategoryDomainObject
    {
        return $this->productCategoryRepository->create(array_filter($productCategoryDomainObject->toArray()));
    }

    public function createDefaultProductCategory(EventDomainObject $event): void
    {
        $this->createCategory((new ProductCategoryDomainObject())
            ->setEventId($event->getId())
            ->setName(__('Tickets'))
            ->setIsHidden(false)
            ->setNoProductsMessage(__('There are no tickets available for this event'))
        );
    }
}

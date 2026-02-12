<?php

namespace Ciencia\Services\Application\Handlers\ProductCategory;

use Ciencia\DomainObjects\ProductCategoryDomainObject;
use Ciencia\Repository\Interfaces\ProductCategoryRepositoryInterface;
use Ciencia\Services\Application\Handlers\ProductCategory\DTO\UpsertProductCategoryDTO;

class EditProductCategoryHandler
{
    public function __construct(
        private readonly ProductCategoryRepositoryInterface $productCategoryRepository,
    )
    {
    }

    public function handle(UpsertProductCategoryDTO $dto): ProductCategoryDomainObject
    {
        $this->productCategoryRepository->updateWhere(
            attributes: [
                'name' => $dto->name,
                'is_hidden' => $dto->is_hidden,
                'description' => $dto->description,
                'no_products_message' => $dto->no_products_message ?? __('There are no products available in this category'),
            ],
            where: [
                'id' => $dto->product_category_id,
                'event_id' => $dto->event_id,
            ],
        );

        return $this->productCategoryRepository->findFirstWhere([
            'id' => $dto->product_category_id,
            'event_id' => $dto->event_id,
        ]);
    }
}

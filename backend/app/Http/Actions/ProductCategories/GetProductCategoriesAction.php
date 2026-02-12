<?php

namespace Ciencia\Http\Actions\ProductCategories;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\ProductCategory\ProductCategoryResource;
use Ciencia\Services\Application\Handlers\ProductCategory\GetProductCategoriesHandler;
use Illuminate\Http\JsonResponse;

class GetProductCategoriesAction extends BaseAction
{
    public function __construct(
        private readonly GetProductCategoriesHandler $getProductCategoriesHandler,
    )
    {
    }

    public function __invoke(int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $categories = $this->getProductCategoriesHandler->handle($eventId);

        return $this->resourceResponse(
            resource: ProductCategoryResource::class,
            data: $categories,
        );
    }
}

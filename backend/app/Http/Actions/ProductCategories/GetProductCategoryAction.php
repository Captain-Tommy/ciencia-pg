<?php

namespace Ciencia\Http\Actions\ProductCategories;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\ProductCategory\ProductCategoryResource;
use Ciencia\Services\Application\Handlers\ProductCategory\GetProductCategoryHandler;
use Illuminate\Http\JsonResponse;

class GetProductCategoryAction extends BaseAction
{
    public function __construct(
        private readonly GetProductCategoryHandler $getProductCategoryHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $productCategoryId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $category = $this->getProductCategoryHandler->handle($eventId, $productCategoryId);

        return $this->resourceResponse(
            resource: ProductCategoryResource::class,
            data: $category,
        );
    }
}

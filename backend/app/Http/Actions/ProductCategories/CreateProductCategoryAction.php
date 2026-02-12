<?php

namespace Ciencia\Http\Actions\ProductCategories;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\ProductCategory\UpsertProductCategoryRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\ProductCategory\ProductCategoryResource;
use Ciencia\Services\Application\Handlers\ProductCategory\CreateProductCategoryHandler;
use Ciencia\Services\Application\Handlers\ProductCategory\DTO\UpsertProductCategoryDTO;
use Illuminate\Http\JsonResponse;

class CreateProductCategoryAction extends BaseAction
{
    public function __construct(
        private readonly CreateProductCategoryHandler $handler
    )
    {
    }

    public function __invoke(UpsertProductCategoryRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $productCategory = $this->handler->handle(new UpsertProductCategoryDTO(
            name: $request->validated('name'),
            description: $request->validated('description'),
            is_hidden: $request->validated('is_hidden'),
            event_id: $eventId,
            no_products_message: $request->validated('no_products_message'),
        ));

        return $this->resourceResponse(
            resource: ProductCategoryResource::class,
            data: $productCategory,
            statusCode: ResponseCodes::HTTP_CREATED,
        );
    }
}

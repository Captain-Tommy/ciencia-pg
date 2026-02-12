<?php

namespace Ciencia\Http\Actions\ProductCategories;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\CannotDeleteEntityException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\ProductCategory\DeleteProductCategoryHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class DeleteProductCategoryAction extends BaseAction
{
    public function __construct(
        private readonly DeleteProductCategoryHandler $deleteProductCategoryHandler,
    )
    {
    }

    /**
     * @throws Throwable
     * @throws CannotDeleteEntityException
     */
    public function __invoke(
        int $eventId,
        int $productCategoryId,
    ): Response|JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->deleteProductCategoryHandler->handle(
                productCategoryId: $productCategoryId,
                eventId: $eventId,
            );
        } catch (CannotDeleteEntityException $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
                statusCode: Response::HTTP_CONFLICT,
            );
        }

        return $this->deletedResponse();
    }
}

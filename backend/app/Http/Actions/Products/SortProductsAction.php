<?php

namespace Ciencia\Http\Actions\Products;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Product\SortProductsRequest;
use Ciencia\Services\Application\Handlers\Product\SortProductsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SortProductsAction extends BaseAction
{
    public function __construct(
        private readonly SortProductsHandler $sortProductsHandler
    )
    {
    }

    public function __invoke(SortProductsRequest $request, int $eventId): Response|JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->sortProductsHandler->handle(
                $eventId,
                $request->validated('sorted_categories'),
            );
        } catch (ResourceConflictException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->noContentResponse();
    }

}

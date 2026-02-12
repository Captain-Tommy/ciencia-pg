<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Products;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\InvalidTaxOrFeeIdException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Product\UpsertProductRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Product\ProductResource;
use Ciencia\Services\Application\Handlers\Product\CreateProductHandler;
use Ciencia\Services\Application\Handlers\Product\DTO\UpsertProductDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateProductAction extends BaseAction
{
    private CreateProductHandler $createProductHandler;

    public function __construct(CreateProductHandler $handler)
    {
        $this->createProductHandler = $handler;
    }

    /**
     * @throws Throwable
     */
    public function __invoke(int $eventId, UpsertProductRequest $request): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $request->merge([
            'event_id' => $eventId,
            'account_id' => $this->getAuthenticatedAccountId(),
        ]);

        try {
            $product = $this->createProductHandler->handle(UpsertProductDTO::fromArray($request->all()));
        } catch (InvalidTaxOrFeeIdException $e) {
            throw ValidationException::withMessages([
                'tax_and_fee_ids' => $e->getMessage(),
            ]);
        }

        return $this->resourceResponse(
            resource: ProductResource::class,
            data: $product,
            statusCode: ResponseCodes::HTTP_CREATED,
        );
    }
}

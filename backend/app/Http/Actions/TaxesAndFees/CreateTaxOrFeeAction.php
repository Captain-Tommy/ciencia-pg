<?php

namespace Ciencia\Http\Actions\TaxesAndFees;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\Exceptions\ResourceNameAlreadyExistsException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\TaxOrFee\CreateTaxOrFeeRequest;
use Ciencia\Resources\Tax\TaxAndFeeResource;
use Ciencia\Services\Application\Handlers\TaxAndFee\CreateTaxOrFeeHandler;
use Ciencia\Services\Application\Handlers\TaxAndFee\DTO\UpsertTaxDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateTaxOrFeeAction extends BaseAction
{
    private CreateTaxOrFeeHandler $taxHandler;

    public function __construct(CreateTaxOrFeeHandler $taxHandler)
    {
        $this->taxHandler = $taxHandler;
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CreateTaxOrFeeRequest $request, int $accountId): JsonResponse
    {
        $this->isActionAuthorized($accountId, AccountDomainObject::class);

        try {
            $payload = array_merge($request->validated(), [
                'account_id' => $this->getAuthenticatedAccountId(),
            ]);

            $tax = $this->taxHandler->handle(UpsertTaxDTO::fromArray($payload));
        } catch (ResourceNameAlreadyExistsException $e) {
            throw ValidationException::withMessages([
                'name' => $e->getMessage(),
            ]);
        }

        return $this->resourceResponse(TaxAndFeeResource::class, $tax);
    }
}

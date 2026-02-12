<?php

namespace Ciencia\Http\Actions\TaxesAndFees;

use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\TaxAndFee\DeleteTaxHandler;
use Ciencia\Services\Application\Handlers\TaxAndFee\DTO\DeleteTaxDTO;
use Illuminate\Http\Response;
use Throwable;

class DeleteTaxOrFeeAction extends BaseAction
{
    private DeleteTaxHandler $deleteTaxHandler;

    public function __construct(DeleteTaxHandler $deleteTaxHandler)
    {
        $this->deleteTaxHandler = $deleteTaxHandler;
    }

    /**
     * @throws Throwable
     * @throws ResourceConflictException
     */
    public function __invoke(int $accountId, int $taxOrFeeId): Response
    {
        $this->isActionAuthorized($taxOrFeeId, TaxAndFeesDomainObject::class);

        $this->deleteTaxHandler->handle(new DeleteTaxDTO(
            taxId: $taxOrFeeId,
            accountId: $this->getAuthenticatedAccountId(),
        ));

        return $this->deletedResponse();
    }
}

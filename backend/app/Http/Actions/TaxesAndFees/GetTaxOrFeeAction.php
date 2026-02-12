<?php

namespace Ciencia\Http\Actions\TaxesAndFees;

use Ciencia\DomainObjects\AccountDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\TaxAndFeeRepositoryInterface;
use Ciencia\Resources\Tax\TaxAndFeeResource;
use Illuminate\Http\JsonResponse;

class GetTaxOrFeeAction extends BaseAction
{
    private TaxAndFeeRepositoryInterface $taxAndFeeRepository;

    public function __construct(TaxAndFeeRepositoryInterface $taxAndFeeRepository)
    {
        $this->taxAndFeeRepository = $taxAndFeeRepository;
    }

    public function __invoke(int $accountId): JsonResponse
    {
        $this->isActionAuthorized($accountId, AccountDomainObject::class);

        $tax = $this->taxAndFeeRepository->findWhere([
            'account_id' => $this->getAuthenticatedAccountId(),
        ]);

        return $this->resourceResponse(TaxAndFeeResource::class, $tax);
    }
}

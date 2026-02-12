<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Affiliates;

use Ciencia\DomainObjects\AffiliateDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Interfaces\AffiliateRepositoryInterface;
use Ciencia\Resources\Affiliate\AffiliateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAffiliatesAction extends BaseAction
{
    public function __construct(private readonly AffiliateRepositoryInterface $affiliateRepository)
    {
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $affiliates = $this->affiliateRepository->findByEventId($eventId, QueryParamsDTO::fromArray($request->query->all()));

        return $this->filterableResourceResponse(
            resource: AffiliateResource::class,
            data: $affiliates,
            domainObject: AffiliateDomainObject::class
        );
    }
}

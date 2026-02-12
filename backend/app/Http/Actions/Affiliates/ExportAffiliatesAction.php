<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Affiliates;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exports\AffiliatesExport;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Interfaces\AffiliateRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportAffiliatesAction extends BaseAction
{
    public function __construct(
        private readonly AffiliateRepositoryInterface $affiliateRepository,
        private readonly AffiliatesExport             $export
    )
    {
    }

    public function __invoke(int $eventId): BinaryFileResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $affiliates = $this->affiliateRepository->findByEventId($eventId, new QueryParamsDTO(
            page: 1,
            per_page: 10000,
        ));

        return Excel::download(
            $this->export->withData($affiliates),
            'affiliates.xlsx'
        );
    }
}
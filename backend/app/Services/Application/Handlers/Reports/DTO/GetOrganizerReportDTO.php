<?php

namespace Ciencia\Services\Application\Handlers\Reports\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\Enums\OrganizerReportTypes;

class GetOrganizerReportDTO extends BaseDataObject
{
    public function __construct(
        public readonly int                   $organizerId,
        public readonly OrganizerReportTypes  $reportType,
        public readonly ?string               $startDate,
        public readonly ?string               $endDate,
        public readonly ?string               $currency,
        public readonly ?int                  $eventId = null,
        public readonly int                   $page = 1,
        public readonly int                   $perPage = 1000,
    )
    {
    }
}

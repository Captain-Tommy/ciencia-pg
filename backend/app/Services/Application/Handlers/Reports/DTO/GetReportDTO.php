<?php

namespace Ciencia\Services\Application\Handlers\Reports\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Enums\ReportTypes;

class GetReportDTO extends BaseDTO
{
    public function __construct(
        public readonly int         $eventId,
        public readonly ReportTypes $reportType,
        public readonly ?string     $startDate,
        public readonly ?string     $endDate
    )
    {
    }
}

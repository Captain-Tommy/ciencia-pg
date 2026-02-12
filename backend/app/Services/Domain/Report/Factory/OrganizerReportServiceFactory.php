<?php

namespace Ciencia\Services\Domain\Report\Factory;

use Ciencia\DomainObjects\Enums\OrganizerReportTypes;
use Ciencia\Services\Domain\Report\AbstractOrganizerReportService;
use Ciencia\Services\Domain\Report\OrganizerReports\CheckInSummaryReport;
use Ciencia\Services\Domain\Report\OrganizerReports\EventsPerformanceReport;
use Ciencia\Services\Domain\Report\OrganizerReports\PlatformFeesReport;
use Ciencia\Services\Domain\Report\OrganizerReports\RevenueSummaryReport;
use Ciencia\Services\Domain\Report\OrganizerReports\TaxSummaryReport;
use Illuminate\Support\Facades\App;

class OrganizerReportServiceFactory
{
    public function create(OrganizerReportTypes $reportType): AbstractOrganizerReportService|PlatformFeesReport
    {
        return match ($reportType) {
            OrganizerReportTypes::REVENUE_SUMMARY => App::make(RevenueSummaryReport::class),
            OrganizerReportTypes::EVENTS_PERFORMANCE => App::make(EventsPerformanceReport::class),
            OrganizerReportTypes::TAX_SUMMARY => App::make(TaxSummaryReport::class),
            OrganizerReportTypes::CHECK_IN_SUMMARY => App::make(CheckInSummaryReport::class),
            OrganizerReportTypes::PLATFORM_FEES => App::make(PlatformFeesReport::class),
        };
    }
}

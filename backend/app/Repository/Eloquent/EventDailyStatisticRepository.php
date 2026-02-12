<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\EventDailyStatisticDomainObject;
use Ciencia\Models\EventDailyStatistic;
use Ciencia\Repository\Interfaces\EventDailyStatisticRepositoryInterface;

class EventDailyStatisticRepository extends BaseRepository implements EventDailyStatisticRepositoryInterface
{
    protected function getModel(): string
    {
        return EventDailyStatistic::class;
    }

    public function getDomainObject(): string
    {
        return EventDailyStatisticDomainObject::class;
    }
}

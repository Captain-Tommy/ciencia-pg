<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\EventStatisticDomainObject;
use Ciencia\Models\EventStatistic;
use Ciencia\Repository\Interfaces\EventStatisticRepositoryInterface;

class EventStatisticRepository extends BaseRepository implements EventStatisticRepositoryInterface
{
    protected function getModel(): string
    {
        return EventStatistic::class;
    }

    public function getDomainObject(): string
    {
        return EventStatisticDomainObject::class;
    }
}

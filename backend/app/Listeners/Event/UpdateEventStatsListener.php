<?php

namespace Ciencia\Listeners\Event;

use Ciencia\Events\OrderStatusChangedEvent;
use Ciencia\Jobs\Event\UpdateEventStatisticsJob;

class UpdateEventStatsListener
{
    public function handle(OrderStatusChangedEvent $changedEvent): void
    {
        if (!$changedEvent->order->isOrderCompleted()) {
            return;
        }

        dispatch(new UpdateEventStatisticsJob($changedEvent->order));
    }
}

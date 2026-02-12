<?php

namespace Ciencia\Listeners\Order;

use Ciencia\Events\OrderStatusChangedEvent;
use Ciencia\Jobs\Order\SendOrderDetailsEmailJob;

class SendOrderDetailsEmailListener
{
    public function handle(OrderStatusChangedEvent $changedEvent): void
    {
        if (!$changedEvent->sendEmails) {
            return;
        }

        dispatch(new SendOrderDetailsEmailJob($changedEvent->order));
    }
}

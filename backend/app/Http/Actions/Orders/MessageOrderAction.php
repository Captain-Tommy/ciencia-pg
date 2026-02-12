<?php

namespace Ciencia\Http\Actions\Orders;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Message\SendMessageRequest;
use Ciencia\Jobs\Event\SendMessagesJob;
use Illuminate\Http\Response;

class MessageOrderAction extends BaseAction
{
    public function __invoke(SendMessageRequest $request, int $eventId, int $orderId): Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        SendMessagesJob::dispatch($orderId, $request->input('subject'), $request->input('message'));

        return $this->noContentResponse();
    }
}

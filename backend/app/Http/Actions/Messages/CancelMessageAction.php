<?php

namespace Ciencia\Http\Actions\Messages;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Message\MessageResource;
use Ciencia\Services\Application\Handlers\Message\CancelMessageHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancelMessageAction extends BaseAction
{
    public function __construct(
        private readonly CancelMessageHandler $cancelMessageHandler,
    )
    {
    }

    public function __invoke(Request $request, int $eventId, int $messageId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $message = $this->cancelMessageHandler->handle($messageId, $eventId);

        return $this->resourceResponse(MessageResource::class, $message);
    }
}

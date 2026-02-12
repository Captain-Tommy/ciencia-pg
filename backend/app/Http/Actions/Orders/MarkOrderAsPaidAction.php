<?php

namespace Ciencia\Http\Actions\Orders;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Order\OrderResource;
use Ciencia\Services\Application\Handlers\Order\DTO\MarkOrderAsPaidDTO;
use Ciencia\Services\Application\Handlers\Order\MarkOrderAsPaidHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MarkOrderAsPaidAction extends BaseAction
{
    public function __construct(
        private readonly MarkOrderAsPaidHandler $markOrderAsPaidHandler,
    )
    {
    }

    public function __invoke(int $eventId, int $orderId): JsonResponse|Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $order = $this->markOrderAsPaidHandler->handle(new MarkOrderAsPaidDTO($eventId, $orderId));
        } catch (ResourceConflictException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->resourceResponse(
            resource: OrderResource::class,
            data: $order,
        );
    }
}

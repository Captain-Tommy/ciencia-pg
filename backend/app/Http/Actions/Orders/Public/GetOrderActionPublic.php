<?php

namespace Ciencia\Http\Actions\Orders\Public;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Order\OrderResourcePublic;
use Ciencia\Services\Application\Handlers\Order\DTO\GetOrderPublicDTO;
use Ciencia\Services\Application\Handlers\Order\GetOrderPublicHandler;
use Ciencia\Services\Infrastructure\Session\CheckoutSessionManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetOrderActionPublic extends BaseAction
{
    public function __construct(
        private readonly GetOrderPublicHandler            $getOrderPublicHandler,
        private readonly CheckoutSessionManagementService $sessionService,
    )
    {
    }

    public function __invoke(int $eventId, string $orderShortId, Request $request): JsonResponse
    {
        $order = $this->getOrderPublicHandler->handle(new GetOrderPublicDTO(
            eventId: $eventId,
            orderShortId: $orderShortId,
            includeEventInResponse: $this->isIncludeRequested($request, 'event'),
        ));

        $response = $this->resourceResponse(
            resource: OrderResourcePublic::class,
            data: $order,
        );

        if ($request->query->has('session_identifier')) {
            $response->headers->setCookie(
                $this->sessionService->getSessionCookie()
            );
        }

        return $response;
    }
}

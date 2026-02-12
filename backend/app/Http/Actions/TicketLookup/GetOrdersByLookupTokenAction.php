<?php

namespace Ciencia\Http\Actions\TicketLookup;

use Ciencia\Exceptions\InvalidTicketLookupTokenException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\Order\OrderResourcePublic;
use Ciencia\Services\Application\Handlers\TicketLookup\DTO\GetOrdersByLookupTokenDTO;
use Ciencia\Services\Application\Handlers\TicketLookup\GetOrdersByLookupTokenHandler;
use Illuminate\Http\JsonResponse;

class GetOrdersByLookupTokenAction extends BaseAction
{
    public function __construct(
        private readonly GetOrdersByLookupTokenHandler $getOrdersByLookupTokenHandler,
    ) {
    }

    public function __invoke(string $token): JsonResponse
    {
        try {
            $orders = $this->getOrdersByLookupTokenHandler->handle(
                new GetOrdersByLookupTokenDTO(
                    token: $token,
                )
            );

            return $this->resourceResponse(
                resource: OrderResourcePublic::class,
                data: $orders,
            );
        } catch (InvalidTicketLookupTokenException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
            );
        }
    }
}

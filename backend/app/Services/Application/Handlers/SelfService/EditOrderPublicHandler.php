<?php

namespace Ciencia\Services\Application\Handlers\SelfService;

use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Application\Handlers\SelfService\DTO\EditOrderPublicDTO;
use Ciencia\Services\Domain\SelfService\DTO\EditOrderResultDTO;
use Ciencia\Services\Domain\SelfService\SelfServiceEditOrderService;

class EditOrderPublicHandler
{
    use SelfServiceValidationTrait;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly SelfServiceEditOrderService $selfServiceEditOrderService,
    ) {
    }

    public function handle(EditOrderPublicDTO $dto): EditOrderResultDTO
    {
        $this->loadAndValidateEvent($dto->eventId);
        $order = $this->loadAndValidateOrder($dto->orderShortId, $dto->eventId);

        return $this->selfServiceEditOrderService->editOrder(
            order: $order,
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            email: $dto->email,
            ipAddress: $dto->ipAddress,
            userAgent: $dto->userAgent
        );
    }
}

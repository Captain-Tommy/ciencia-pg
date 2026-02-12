<?php

namespace Ciencia\Services\Application\Handlers\SelfService;

use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Application\Handlers\SelfService\DTO\ResendEmailPublicDTO;
use Ciencia\Services\Domain\SelfService\SelfServiceResendEmailService;

class ResendOrderConfirmationPublicHandler
{
    use SelfServiceValidationTrait;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly SelfServiceResendEmailService $selfServiceResendEmailService,
    ) {
    }

    public function handle(ResendEmailPublicDTO $dto): void
    {
        $this->loadAndValidateEvent($dto->eventId);
        $order = $this->loadAndValidateOrder($dto->orderShortId, $dto->eventId);

        $this->selfServiceResendEmailService->resendOrderConfirmation(
            orderId: $order->getId(),
            eventId: $dto->eventId,
            ipAddress: $dto->ipAddress,
            userAgent: $dto->userAgent
        );
    }
}

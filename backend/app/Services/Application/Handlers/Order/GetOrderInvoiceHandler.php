<?php

namespace Ciencia\Services\Application\Handlers\Order;

use Ciencia\Services\Application\Handlers\Order\DTO\GetOrderInvoiceDTO;
use Ciencia\Services\Domain\Order\DTO\InvoicePdfResponseDTO;
use Ciencia\Services\Domain\Order\GenerateOrderInvoicePDFService;

class GetOrderInvoiceHandler
{
    public function __construct(
        private readonly GenerateOrderInvoicePDFService $generateOrderInvoicePDFService,
    )
    {
    }

    public function handle(GetOrderInvoiceDTO $command): InvoicePdfResponseDTO
    {
        return $this->generateOrderInvoicePDFService->generatePdfFromOrderId(
            orderId: $command->orderId,
            eventId: $command->eventId,
        );
    }
}

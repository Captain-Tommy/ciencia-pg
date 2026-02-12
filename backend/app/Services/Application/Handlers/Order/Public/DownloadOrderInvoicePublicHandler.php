<?php

namespace Ciencia\Services\Application\Handlers\Order\Public;

use Ciencia\Services\Domain\Order\DTO\InvoicePdfResponseDTO;
use Ciencia\Services\Domain\Order\GenerateOrderInvoicePDFService;

class DownloadOrderInvoicePublicHandler
{
    public function __construct(
        private readonly GenerateOrderInvoicePDFService $generateOrderInvoicePDFService,
    )
    {
    }

    public function handle(int $eventId, string $orderShortId): InvoicePdfResponseDTO
    {
        return $this->generateOrderInvoicePDFService->generatePdfFromOrderShortId(
            orderShortId: $orderShortId,
            eventId: $eventId,
        );
    }
}

<?php

namespace Ciencia\Http\Actions\Orders\Public;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Order\Public\DownloadOrderInvoicePublicHandler;
use Illuminate\Http\Response;

class DownloadOrderInvoicePublicAction extends BaseAction
{
    public function __construct(
        private readonly DownloadOrderInvoicePublicHandler $downloadOrderInvoicePublicHandler,
    )
    {
    }

    public function __invoke(int $eventId, string $orderShortId): Response
    {
        $invoice = $this->downloadOrderInvoicePublicHandler->handle(
            eventId: $eventId,
            orderShortId: $orderShortId,
        );

        return $invoice->pdf->stream($invoice->filename);
    }
}

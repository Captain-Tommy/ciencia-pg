<?php

namespace Ciencia\Resources\Order\Invoice;

use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\Resources\BaseResource;

/** @mixin InvoiceDomainObject */
class InvoiceResourcePublic extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'invoice_number' => $this->getInvoiceNumber(),
            'order_id' => $this->getOrderId(),
            'status' => $this->getStatus(),
        ];
    }
}

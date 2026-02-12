<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<InvoiceDomainObject>
 */
interface InvoiceRepositoryInterface extends RepositoryInterface
{
    public function findLatestInvoiceForEvent(int $eventId): ?InvoiceDomainObject;

    public function findLatestInvoiceForOrder(int $orderId): ?InvoiceDomainObject;
}

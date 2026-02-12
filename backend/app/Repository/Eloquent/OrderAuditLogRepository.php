<?php

declare(strict_types=1);

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrderAuditLogDomainObject;
use Ciencia\Models\OrderAuditLog;
use Ciencia\Repository\Interfaces\OrderAuditLogRepositoryInterface;

class OrderAuditLogRepository extends BaseRepository implements OrderAuditLogRepositoryInterface
{
    protected function getModel(): string
    {
        return OrderAuditLog::class;
    }

    public function getDomainObject(): string
    {
        return OrderAuditLogDomainObject::class;
    }
}

<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\OrderAuditLogDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<OrderAuditLogDomainObject>
 */
interface OrderAuditLogRepositoryInterface extends RepositoryInterface
{

}

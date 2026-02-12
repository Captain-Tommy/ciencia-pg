<?php

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\WebhookLogDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<WebhookLogDomainObject>
 */
interface WebhookLogRepositoryInterface extends RepositoryInterface
{
    public function deleteOldLogs(int $webhookId, int $numberToKeep = 20): void;
}

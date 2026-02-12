<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\WebhookLogDomainObject;
use Ciencia\Models\WebhookLog;
use Ciencia\Repository\Interfaces\WebhookLogRepositoryInterface;

class WebhookLogRepository extends BaseRepository implements WebhookLogRepositoryInterface
{
    protected function getModel(): string
    {
        return WebhookLog::class;
    }

    public function getDomainObject(): string
    {
        return WebhookLogDomainObject::class;
    }

    /**
     * @todo This should be a scheduled task
     */
    public function deleteOldLogs(int $webhookId, int $numberToKeep = 20): void
    {
        $query = $this->model->where('webhook_id', $webhookId);

        $totalLogs = $query->count();

        if ($totalLogs > $numberToKeep) {
            $query->orderBy('created_at', 'desc')
                ->skip($numberToKeep)
                ->take($totalLogs - $numberToKeep)
                ->forceDelete();
        }
    }
}

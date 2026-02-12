<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\FailedJobs;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Admin\RetryFailedJobHandler;
use Illuminate\Http\JsonResponse;

class RetryAllFailedJobsAction extends BaseAction
{
    public function __construct(
        private readonly RetryFailedJobHandler $handler,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $count = $this->handler->retryAll();

        return $this->jsonResponse([
            'message' => __('Queued :count jobs for retry', ['count' => $count]),
            'retry_count' => $count,
        ]);
    }
}

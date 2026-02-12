<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Admin\FailedJobs;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Admin\RetryFailedJobHandler;
use Illuminate\Http\JsonResponse;

class RetryFailedJobAction extends BaseAction
{
    public function __construct(
        private readonly RetryFailedJobHandler $handler,
    ) {
    }

    public function __invoke(int $jobId): JsonResponse
    {
        $this->minimumAllowedRole(Role::SUPERADMIN);

        $retried = $this->handler->handle($jobId);

        if (!$retried) {
            return $this->errorResponse(__('Failed job not found'), 404);
        }

        return $this->jsonResponse([
            'message' => __('Job queued for retry'),
        ]);
    }
}

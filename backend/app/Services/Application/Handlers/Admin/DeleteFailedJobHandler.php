<?php

namespace Ciencia\Services\Application\Handlers\Admin;

use Ciencia\Models\FailedJob;

class DeleteFailedJobHandler
{
    public function handle(int $id): bool
    {
        return FailedJob::where('id', $id)->delete() > 0;
    }

    public function deleteAll(): int
    {
        return FailedJob::query()->delete();
    }
}

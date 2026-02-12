<?php

namespace Ciencia\Services\Application\Handlers\Admin\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class GetAdminDashboardDataDTO extends BaseDataObject
{
    public function __construct(
        public readonly int $days = 14,
        public readonly int $limit = 10,
    ) {
    }
}

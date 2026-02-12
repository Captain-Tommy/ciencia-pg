<?php

namespace Ciencia\Services\Application\Handlers\Admin\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class GetUpcomingEventsDTO extends BaseDataObject
{
    public function __construct(
        public readonly int $perPage = 20,
    )
    {
    }
}

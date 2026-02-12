<?php

namespace Ciencia\Services\Application\Handlers\Admin\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class StopImpersonationDTO extends BaseDataObject
{
    public function __construct(
        public readonly int $impersonatorId,
    )
    {
    }
}

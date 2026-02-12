<?php

namespace Ciencia\Services\Application\Handlers\Admin\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class GetAllAccountsDTO extends BaseDataObject
{
    public function __construct(
        public readonly int     $perPage = 20,
        public readonly ?string $search = null,
    )
    {
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Admin\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;

class GetAllOrdersDTO extends BaseDataObject
{
    public function __construct(
        public readonly int     $perPage = 20,
        public readonly ?string $search = null,
        public readonly ?string $sortBy = OrderDomainObjectAbstract::CREATED_AT,
        public readonly ?string $sortDirection = 'desc',
    )
    {
    }
}

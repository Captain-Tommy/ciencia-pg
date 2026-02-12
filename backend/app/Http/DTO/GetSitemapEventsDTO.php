<?php

namespace Ciencia\Http\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class GetSitemapEventsDTO extends BaseDataObject
{
    public function __construct(
        public int $page,
    )
    {
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\Event\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class DeleteEventImageDTO extends BaseDTO
{
    public function __construct(
        public int $eventId,
        public int $imageId,
    )
    {
    }
}

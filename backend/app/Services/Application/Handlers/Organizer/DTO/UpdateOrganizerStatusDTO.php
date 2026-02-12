<?php

namespace Ciencia\Services\Application\Handlers\Organizer\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class UpdateOrganizerStatusDTO extends BaseDTO
{
    public function __construct(
        public string $status,
        public int $organizerId,
        public int $accountId,
    )
    {
    }
}
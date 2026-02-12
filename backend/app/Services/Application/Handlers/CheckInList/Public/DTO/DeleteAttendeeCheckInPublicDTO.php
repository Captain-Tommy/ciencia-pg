<?php

namespace Ciencia\Services\Application\Handlers\CheckInList\Public\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class DeleteAttendeeCheckInPublicDTO extends BaseDTO
{
    public function __construct(
        public string $checkInListShortId,
        public string $checkInShortId,
        public string $checkInUserIpAddress,
    )
    {
    }
}

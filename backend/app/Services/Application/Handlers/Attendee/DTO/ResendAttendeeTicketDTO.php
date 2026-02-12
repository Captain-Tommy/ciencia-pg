<?php

namespace Ciencia\Services\Application\Handlers\Attendee\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class ResendAttendeeTicketDTO extends BaseDTO
{
    public function __construct(
        public int $attendeeId,
        public int $eventId,
    )
    {
    }
}

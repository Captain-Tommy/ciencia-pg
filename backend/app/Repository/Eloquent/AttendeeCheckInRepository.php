<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\AttendeeCheckInDomainObject;
use Ciencia\Models\AttendeeCheckIn;
use Ciencia\Repository\Interfaces\AttendeeCheckInRepositoryInterface;

class AttendeeCheckInRepository extends BaseRepository implements AttendeeCheckInRepositoryInterface
{
    protected function getModel(): string
    {
        return AttendeeCheckIn::class;
    }

    public function getDomainObject(): string
    {
        return AttendeeCheckInDomainObject::class;
    }
}

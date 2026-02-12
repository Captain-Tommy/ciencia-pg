<?php

namespace Ciencia\Services\Application\Handlers\Organizer;

use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrganizerSettingDomainObject;
use Ciencia\Repository\Interfaces\OrganizerRepositoryInterface;

class GetPublicOrganizerHandler
{
    public function __construct(
        private readonly OrganizerRepositoryInterface $organizerRepository
    )
    {
    }

    public function handle(int $organizerId)
    {
        return $this->organizerRepository
            ->loadRelation(ImageDomainObject::class)
            ->loadRelation(OrganizerSettingDomainObject::class)
            ->findById($organizerId);
    }
}

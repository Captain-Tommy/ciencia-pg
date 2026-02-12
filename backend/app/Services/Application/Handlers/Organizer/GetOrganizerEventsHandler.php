<?php

namespace Ciencia\Services\Application\Handlers\Organizer;

use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Services\Application\Handlers\Organizer\DTO\GetOrganizerEventsDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class GetOrganizerEventsHandler
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository
    )
    {
    }

    public function handle(GetOrganizerEventsDTO $dto): LengthAwarePaginator
    {
        return $this->eventRepository
            ->loadRelation(new Relationship(ImageDomainObject::class))
            ->loadRelation(new Relationship(EventSettingDomainObject::class))
            ->loadRelation(new Relationship(
                domainObject: OrganizerDomainObject::class,
                name: 'organizer',
            ))
            ->findEvents(
                where: [
                    'account_id' => $dto->accountId,
                    'organizer_id' => $dto->organizerId,
                ],
                params: $dto->queryParams
            );
    }
}

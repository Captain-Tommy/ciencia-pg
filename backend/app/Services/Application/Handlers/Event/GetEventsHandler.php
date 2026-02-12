<?php

namespace Ciencia\Services\Application\Handlers\Event;

use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\EventStatisticDomainObject;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Services\Application\Handlers\Event\DTO\GetEventsDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class GetEventsHandler
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
    )
    {
    }

    public function handle(GetEventsDTO $dto): LengthAwarePaginator
    {
        return $this->eventRepository
            ->loadRelation(new Relationship(ImageDomainObject::class))
            ->loadRelation(new Relationship(EventSettingDomainObject::class))
            ->loadRelation(new Relationship(EventStatisticDomainObject::class))
            ->loadRelation(new Relationship(
                domainObject: ProductDomainObject::class,
                nested: [
                    new Relationship(ProductPriceDomainObject::class),
                ],
            ))
            ->loadRelation(new Relationship(
                domainObject: OrganizerDomainObject::class,
                name: 'organizer',
            ))
            ->findEvents(
                where: [
                    'account_id' => $dto->accountId,
                ],
                params: $dto->queryParams
            );
    }
}

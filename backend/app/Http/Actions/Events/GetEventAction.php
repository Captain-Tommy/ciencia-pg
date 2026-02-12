<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Events;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\ProductCategoryDomainObject;
use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Resources\Event\EventResource;
use Illuminate\Http\JsonResponse;

class GetEventAction extends BaseAction
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $event = $this->eventRepository
            ->loadRelation(new Relationship(domainObject: OrganizerDomainObject::class, name: 'organizer'))
            ->loadRelation(new Relationship(ImageDomainObject::class))
            ->loadRelation(
                new Relationship(ProductCategoryDomainObject::class, [
                    new Relationship(ProductDomainObject::class, [
                        new Relationship(ProductPriceDomainObject::class),
                        new Relationship(TaxAndFeesDomainObject::class),
                    ]),
                ])
            )
            ->findById($eventId);

        return $this->resourceResponse(EventResource::class, $event);
    }
}

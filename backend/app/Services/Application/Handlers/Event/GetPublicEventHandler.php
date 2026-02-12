<?php

namespace Ciencia\Services\Application\Handlers\Event;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\PromoCodeDomainObjectAbstract;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\OrganizerSettingDomainObject;
use Ciencia\DomainObjects\ProductCategoryDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\TaxAndFeesDomainObject;
use Ciencia\Repository\Eloquent\Value\OrderAndDirection;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\PromoCodeRepositoryInterface;
use Ciencia\Services\Application\Handlers\Event\DTO\GetPublicEventDTO;
use Ciencia\Services\Domain\Event\EventPageViewIncrementService;
use Ciencia\Services\Domain\Product\ProductFilterService;

class GetPublicEventHandler
{
    public function __construct(
        private readonly EventRepositoryInterface      $eventRepository,
        private readonly PromoCodeRepositoryInterface  $promoCodeRepository,
        private readonly ProductFilterService          $productFilterService,
        private readonly EventPageViewIncrementService $eventPageViewIncrementService,
    )
    {
    }

    public function handle(GetPublicEventDTO $data): EventDomainObject
    {
        $event = $this->eventRepository
            ->loadRelation(
                new Relationship(ProductCategoryDomainObject::class, [
                    new Relationship(ProductDomainObject::class,
                        nested: [
                            new Relationship(ProductPriceDomainObject::class),
                            new Relationship(TaxAndFeesDomainObject::class),
                        ],
                        orderAndDirections: [
                            new OrderAndDirection('order', 'asc'),
                        ]
                    ),
                ])
            )
            ->loadRelation(new Relationship(EventSettingDomainObject::class))
            ->loadRelation(new Relationship(ImageDomainObject::class))
            ->loadRelation(new Relationship(OrganizerDomainObject::class, nested: [
                new Relationship(ImageDomainObject::class),
                new Relationship(OrganizerSettingDomainObject::class),
            ], name: 'organizer'))
            ->findById($data->eventId);

        $promoCodeDomainObject = $this->promoCodeRepository->findFirstWhere([
            PromoCodeDomainObjectAbstract::EVENT_ID => $data->eventId,
            PromoCodeDomainObjectAbstract::CODE => $data->promoCode,
        ]);

        if (!$promoCodeDomainObject?->isValid()) {
            $promoCodeDomainObject = null;
        }

        if (!$data->isAuthenticated) {
            $this->eventPageViewIncrementService->increment($data->eventId, $data->ipAddress);
        }

        return $event->setProductCategories($this->productFilterService->filter(
            productsCategories: $event->getProductCategories(),
            promoCode: $promoCodeDomainObject
        ));
    }
}

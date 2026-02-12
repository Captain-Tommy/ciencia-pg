<?php

namespace Ciencia\Services\Application\Handlers\TicketLookup;

use Carbon\Carbon;
use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\EventDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\OrderDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\OrganizerDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\ProductDomainObjectAbstract;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\Status\OrderStatus;
use Ciencia\DomainObjects\TicketLookupTokenDomainObject;
use Ciencia\Exceptions\InvalidTicketLookupTokenException;
use Ciencia\Repository\Eloquent\Value\OrderAndDirection;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Repository\Interfaces\TicketLookupTokenRepositoryInterface;
use Ciencia\Services\Application\Handlers\TicketLookup\DTO\GetOrdersByLookupTokenDTO;
use Illuminate\Support\Collection;

class GetOrdersByLookupTokenHandler
{
    public function __construct(
        private readonly TicketLookupTokenRepositoryInterface $ticketLookupTokenRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    /**
     * @throws InvalidTicketLookupTokenException
     * @return Collection<OrderDomainObject>
     */
    public function handle(GetOrdersByLookupTokenDTO $dto): Collection
    {
        $tokenRecord = $this->validateAndFetchToken($dto->token);

        return $this->getOrdersForEmail($tokenRecord->getEmail());
    }

    /**
     * @throws InvalidTicketLookupTokenException
     */
    private function validateAndFetchToken(string $token): TicketLookupTokenDomainObject
    {
        $tokenRecord = $this->ticketLookupTokenRepository->findFirstWhere(['token' => $token]);

        if (!$tokenRecord) {
            throw new InvalidTicketLookupTokenException(__('Invalid or expired link. Please request a new one.'));
        }

        if ($this->isTokenExpired($tokenRecord->getExpiresAt())) {
            throw new InvalidTicketLookupTokenException(__('This link has expired. Please request a new one.'));
        }

        return $tokenRecord;
    }

    private function isTokenExpired(string $expiresAt): bool
    {
        return (new Carbon($expiresAt))->isPast();
    }

    /**
     * @return Collection<OrderDomainObject>
     */
    private function getOrdersForEmail(string $email): Collection
    {
        return $this->orderRepository
            ->loadRelation(new Relationship(
                domainObject: AttendeeDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: ProductDomainObject::class,
                        nested: [
                            new Relationship(
                                domainObject: ProductPriceDomainObject::class,
                            )
                        ],
                        name: ProductDomainObjectAbstract::SINGULAR_NAME,
                    )
                ],
            ))
            ->loadRelation(new Relationship(
                domainObject: EventDomainObject::class,
                nested: [
                    new Relationship(
                        domainObject: EventSettingDomainObject::class,
                    ),
                    new Relationship(
                        domainObject: OrganizerDomainObject::class,
                        name: OrganizerDomainObjectAbstract::SINGULAR_NAME,
                    ),
                    new Relationship(
                        domainObject: ImageDomainObject::class,
                    )
                ],
                name: EventDomainObjectAbstract::SINGULAR_NAME
            ))
            ->findWhere(
                [
                    [OrderDomainObjectAbstract::EMAIL, '=', $email],
                    [OrderDomainObjectAbstract::STATUS, '=', OrderStatus::COMPLETED->name],
                ],
                orderAndDirections: [
                    new OrderAndDirection(OrderDomainObjectAbstract::CREATED_AT, 'desc'),
                ],
            );
    }
}

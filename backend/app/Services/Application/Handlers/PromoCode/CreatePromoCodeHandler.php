<?php

namespace Ciencia\Services\Application\Handlers\PromoCode;

use Ciencia\DomainObjects\PromoCodeDomainObject;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Services\Application\Handlers\PromoCode\DTO\UpsertPromoCodeDTO;
use Ciencia\Services\Domain\Product\Exception\UnrecognizedProductIdException;
use Ciencia\Services\Domain\PromoCode\CreatePromoCodeService;

readonly class CreatePromoCodeHandler
{
    public function __construct(
        private CreatePromoCodeService $createPromoCodeService,
    )
    {
    }

    /**
     * @throws ResourceConflictException
     * @throws UnrecognizedProductIdException
     */
    public function handle(int $eventId, UpsertPromoCodeDTO $promoCodeDTO): PromoCodeDomainObject
    {
        return $this->createPromoCodeService->createPromoCode(
            (new PromoCodeDomainObject())
                ->setEventId($eventId)
                ->setCode($promoCodeDTO->code)
                ->setDiscountType($promoCodeDTO->discount_type->name)
                ->setDiscount($promoCodeDTO->discount)
                ->setExpiryDate($promoCodeDTO->expiry_date)
                ->setMaxAllowedUsages($promoCodeDTO->max_allowed_usages)
                ->setApplicableProductIds($promoCodeDTO->applicable_product_ids)
        );
    }
}

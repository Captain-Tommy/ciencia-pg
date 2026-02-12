<?php

declare(strict_types=1);

namespace Ciencia\Services\Application\Handlers\Affiliate;

use Ciencia\DomainObjects\AffiliateDomainObject;
use Ciencia\Repository\Interfaces\AffiliateRepositoryInterface;
use Ciencia\Services\Application\Handlers\Affiliate\DTO\UpsertAffiliateDTO;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateAffiliateHandler
{
    public function __construct(
        private readonly AffiliateRepositoryInterface $affiliateRepository,
    )
    {
    }

    public function handle(int $affiliateId, int $eventId, UpsertAffiliateDTO $dto): AffiliateDomainObject
    {
        $existingAffiliate = $this->affiliateRepository->findFirstWhere([
            'id' => $affiliateId,
            'event_id' => $eventId
        ]);

        if (!$existingAffiliate) {
            throw new NotFoundHttpException(__('Affiliate not found'));
        }

        $updateData = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'status' => $dto->status->value,
        ], static fn($value) => $value !== null);

        return $this->affiliateRepository->updateFromArray($affiliateId, $updateData);
    }
}

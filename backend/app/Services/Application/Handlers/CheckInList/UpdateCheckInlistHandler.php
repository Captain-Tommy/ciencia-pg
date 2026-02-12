<?php

namespace Ciencia\Services\Application\Handlers\CheckInList;

use Ciencia\DomainObjects\CheckInListDomainObject;
use Ciencia\Services\Application\Handlers\CheckInList\DTO\UpsertCheckInListDTO;
use Ciencia\Services\Domain\CheckInList\UpdateCheckInListService;
use Ciencia\Services\Domain\Product\Exception\UnrecognizedProductIdException;

class UpdateCheckInlistHandler
{
    public function __construct(
        private readonly UpdateCheckInlistService $updateCheckInlistService,
    )
    {
    }

    /**
     * @throws UnrecognizedProductIdException
     */
    public function handle(UpsertCheckInListDTO $data): CheckInListDomainObject
    {
        $checkInList = (new CheckInListDomainObject())
            ->setId($data->id)
            ->setName($data->name)
            ->setDescription($data->description)
            ->setEventId($data->eventId)
            ->setExpiresAt($data->expiresAt)
            ->setActivatesAt($data->activatesAt);

        return $this->updateCheckInlistService->updateCheckInlist(
            checkInList: $checkInList,
            productIds: $data->productIds
        );
    }
}

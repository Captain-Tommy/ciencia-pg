<?php

namespace Ciencia\Services\Application\Handlers\Event;

use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\Services\Application\Handlers\Event\DTO\CreateEventImageDTO;
use Ciencia\Services\Domain\Event\CreateEventImageService;
use Throwable;

class CreateEventImageHandler
{
    public function __construct(
        private readonly CreateEventImageService $createEventImageService,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(CreateEventImageDTO $imageData): ImageDomainObject
    {
        return $this->createEventImageService->createImage(
            eventId: $imageData->eventId,
            accountId: $imageData->accountId,
            image: $imageData->image,
            imageType: $imageData->imageType,
        );
    }
}

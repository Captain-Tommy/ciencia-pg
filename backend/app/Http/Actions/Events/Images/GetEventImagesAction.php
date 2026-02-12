<?php

namespace Ciencia\Http\Actions\Events\Images;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\ImageRepositoryInterface;
use Ciencia\Resources\Image\ImageResource;
use Illuminate\Http\JsonResponse;

class GetEventImagesAction extends BaseAction
{
    public function __construct(private readonly ImageRepositoryInterface $imageRepository)
    {
    }

    public function __invoke(int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $images = $this->imageRepository->findWhere([
            'entity_id' => $eventId,
            'entity_type' => EventDomainObject::class,
        ]);

        return $this->resourceResponse(ImageResource::class, $images);
    }
}

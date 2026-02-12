<?php

namespace Ciencia\Http\Actions\Events\Images;

use Ciencia\DomainObjects\Enums\ImageType;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Event\CreateEventImageRequest;
use Ciencia\Resources\Image\ImageResource;
use Ciencia\Services\Application\Handlers\Event\CreateEventImageHandler;
use Ciencia\Services\Application\Handlers\Event\DTO\CreateEventImageDTO;
use Illuminate\Http\JsonResponse;

class CreateEventImageAction extends BaseAction
{
    private CreateEventImageHandler $createEventImageHandler;

    public function __construct(CreateEventImageHandler $createEventImageHandler)
    {
        $this->createEventImageHandler = $createEventImageHandler;
    }

    public function __invoke(CreateEventImageRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $payload = array_merge($request->validated(), [
            'event_id' => $eventId,
        ]);

        $image = $this->createEventImageHandler->handle(new CreateEventImageDTO(
            eventId: $payload['event_id'],
            accountId: $this->getAuthenticatedAccountId(),
            image: $request->file('image'),
            imageType: ImageType::fromName($payload['type']),
        ));

        return $this->resourceResponse(ImageResource::class, $image);
    }
}

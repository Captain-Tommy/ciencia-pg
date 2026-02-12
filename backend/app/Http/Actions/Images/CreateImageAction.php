<?php

namespace Ciencia\Http\Actions\Images;

use Ciencia\DomainObjects\Enums\ImageType;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Image\CreateImageRequest;
use Ciencia\Resources\Image\ImageResource;
use Ciencia\Services\Application\Handlers\Images\CreateImageHandler;
use Ciencia\Services\Application\Handlers\Images\DTO\CreateImageDTO;
use Ciencia\Services\Infrastructure\Image\Exception\CouldNotUploadImageException;
use Illuminate\Http\JsonResponse;

class CreateImageAction extends BaseAction
{
    public function __construct(
        public readonly CreateImageHandler $createImageHandler,
    )
    {
    }

    /**
     * @throws CouldNotUploadImageException
     */
    public function __invoke(CreateImageRequest $request): JsonResponse
    {
        $image = $this->createImageHandler->handle(new CreateImageDTO(
            userId: $this->getAuthenticatedUser()->getId(),
            accountId: $this->getAuthenticatedAccountId(),
            image: $request->file('image'),
            imageType: $request->has('image_type') ? ImageType::fromName($request->input('image_type')) : null,
            entityId: $request->input('entity_id'),
        ));

        return $this->resourceResponse(ImageResource::class, $image);
    }
}

<?php

namespace Ciencia\Http\Actions\Images;

use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\Exceptions\CannotDeleteEntityException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Images\DeleteImageHandler;
use Ciencia\Services\Application\Handlers\Images\DTO\DeleteImageDTO;
use Illuminate\Http\Response;

class DeleteImageAction extends BaseAction
{
    public function __construct(
        public readonly DeleteImageHandler $deleteImageHandler,
    )
    {
    }

    /**
     * @throws CannotDeleteEntityException
     */
    public function __invoke(int $imageId): Response
    {
        $this->isActionAuthorized($imageId, ImageDomainObject::class);

        $this->deleteImageHandler->handle(new DeleteImageDTO(
            imageId: $imageId,
            userId: $this->getAuthenticatedUser()->getId(),
            accountId: $this->getAuthenticatedAccountId(),
        ));

        return $this->noContentResponse();
    }
}

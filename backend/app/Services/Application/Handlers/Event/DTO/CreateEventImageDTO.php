<?php

namespace Ciencia\Services\Application\Handlers\Event\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Enums\ImageType;
use Illuminate\Http\UploadedFile;

class CreateEventImageDTO extends BaseDTO
{
    public function __construct(
        public readonly int          $eventId,
        public readonly int          $accountId,
        public readonly UploadedFile $image,
        public readonly ImageType    $imageType,
    )
    {
    }
}

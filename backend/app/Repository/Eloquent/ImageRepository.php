<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\Models\Image;
use Ciencia\Repository\Interfaces\ImageRepositoryInterface;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    protected function getModel(): string
    {
        return Image::class;
    }

    public function getDomainObject(): string
    {
        return ImageDomainObject::class;
    }
}

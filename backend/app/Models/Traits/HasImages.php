<?php

namespace Ciencia\Models\Traits;

use Ciencia\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public function images(): MorphMany
    {
        return $this->morphMany(related: Image::class, name: 'entity');
    }
}

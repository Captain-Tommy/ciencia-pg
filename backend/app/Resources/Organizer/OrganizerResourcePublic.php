<?php

namespace Ciencia\Resources\Organizer;

use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Resources\Event\EventResourcePublic;
use Ciencia\Resources\Image\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrganizerDomainObject
 */
class OrganizerResourcePublic extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'website' => $this->getWebsite(),
            'description' => $this->getDescription(),
            'slug' => $this->getSlug(),
            'status' => $this->getStatus(),
            'images' => $this->when(
                (bool)$this->getImages(),
                fn() => ImageResource::collection($this->getImages())
            ),
            'events' => $this->when(
                condition: !is_null($this->getEvents()),
                value: fn() => EventResourcePublic::collection($this->getEvents())
            ),
            'settings' => $this->when(
                condition: !is_null($this->getOrganizerSettings()),
                value: fn() => new OrganizerSettingsPublicResource($this->getOrganizerSettings())
            ),
        ];
    }
}

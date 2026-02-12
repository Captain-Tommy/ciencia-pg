<?php

namespace Ciencia\Services\Application\Handlers\Event\DTO;

use Ciencia\DataTransferObjects\AddressDTO;
use Ciencia\DataTransferObjects\Attributes\CollectionOf;
use Ciencia\DataTransferObjects\AttributesDTO;
use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Enums\EventCategory;
use Ciencia\DomainObjects\Status\EventStatus;
use Ciencia\Services\Application\Handlers\EventSettings\DTO\UpdateEventSettingsDTO;
use Illuminate\Support\Collection;

class CreateEventDTO extends BaseDTO
{
    public function __construct(
        public readonly string         $title,
        public readonly int            $organizer_id,
        public readonly int            $account_id,
        public readonly int            $user_id,
        public readonly ?int           $id = null,
        public readonly ?string        $start_date = null,
        public readonly ?string        $end_date = null,
        public readonly ?string        $description = null,
        #[CollectionOf(AttributesDTO::class)]
        public readonly ?Collection    $attributes = null,
        public readonly ?string        $timezone = null,
        public readonly ?string        $currency = null,
        public readonly ?EventCategory $category = null,
        public readonly ?AddressDTO    $location_details = null,
        public readonly ?string        $status = EventStatus::DRAFT->name,

        public ?UpdateEventSettingsDTO $event_settings = null
    )
    {
    }
}

<?php

namespace Ciencia\Services\Application\Handlers\EventSettings\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class PartialUpdateEventSettingsDTO extends BaseDTO
{
    public function __construct(
        public readonly int   $account_id,
        public readonly int   $event_id,
        public readonly array $settings,
    )
    {
    }
}

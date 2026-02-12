<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\Models\EventSetting;
use Ciencia\Repository\Interfaces\EventSettingsRepositoryInterface;

class EventSettingsRepository extends BaseRepository implements EventSettingsRepositoryInterface
{
    protected function getModel(): string
    {
        return EventSetting::class;
    }

    public function getDomainObject(): string
    {
        return EventSettingDomainObject::class;
    }
}

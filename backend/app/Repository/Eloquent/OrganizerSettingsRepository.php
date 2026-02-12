<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OrganizerSettingDomainObject;
use Ciencia\Models\OrganizerSetting;
use Ciencia\Repository\Interfaces\OrganizerSettingsRepositoryInterface;

class OrganizerSettingsRepository extends BaseRepository implements OrganizerSettingsRepositoryInterface
{
    protected function getModel(): string
    {
        return OrganizerSetting::class;
    }

    public function getDomainObject(): string
    {
        return OrganizerSettingDomainObject::class;
    }
}

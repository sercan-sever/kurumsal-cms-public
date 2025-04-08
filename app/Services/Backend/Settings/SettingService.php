<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings;

use App\Interfaces\Settings\SettingInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService implements SettingInterface
{
    /**
     * @return Setting
     */
    public function getSetting(): Setting
    {
        return Cache::remember('setting', 3600, function () { // 1 saat boyunca cacheleme yapıyor.
            return Setting::query()
            ->with(['social', 'logo', 'plugin', 'email'])
            ->firstOrCreate();
        });
    }
}

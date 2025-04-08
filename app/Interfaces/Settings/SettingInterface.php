<?php

declare(strict_types=1);

namespace App\Interfaces\Settings;

use App\Models\Setting;

interface SettingInterface
{
    /**
     * @return Setting
     */
    public function getSetting(): Setting;
}

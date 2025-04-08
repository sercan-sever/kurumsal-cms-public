<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Plugin;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\Plugin\PluginInterface;
use App\Models\PluginSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PluginService implements PluginInterface
{
    /**
     * @return PluginSetting|null
     */
    public function getModel(): ?PluginSetting
    {
        return PluginSetting::query()->first();
    }


    /**
     * @param int $id
     *
     * @return PluginSetting|null
     */
    public function getPluginById(int $id): ?PluginSetting
    {
        return PluginSetting::query()->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $socialUpdateDTO
     *
     * @return PluginSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $pluginUpdateDTO): ?PluginSetting
    {
        try {
            return PluginSetting::query()->updateOrCreate(
                ['setting_id' => $pluginUpdateDTO->settingId],
                [

                    'recaptcha_site_key'   => $pluginUpdateDTO?->siteKey,
                    'recaptcha_secret_key' => $pluginUpdateDTO?->secretKey,
                    'analytics_four'       => $pluginUpdateDTO?->analytic,
                    'updated_by'           => request()->user()->id,
                    'updated_at'           => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("PluginService (createOrUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

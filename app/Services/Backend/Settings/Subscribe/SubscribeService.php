<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\Subscribe\SubscribeInterface;
use App\Models\SubscribeSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscribeService implements SubscribeInterface
{
    /**
     * @return SubscribeSetting|null
     */
    public function getModel(): ?SubscribeSetting
    {
        return SubscribeSetting::query()->first();
    }

    /**
     * @param BaseDTOInterface $subscribeUpdateDTO
     * @param int $settingId
     *
     * @return SubscribeSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $subscribeUpdateDTO, int $settingId): ?SubscribeSetting
    {
        try {
            return SubscribeSetting::query()->updateOrCreate(
                ['setting_id' => $settingId],
                [
                    'status'     => $subscribeUpdateDTO->status,
                    'updated_by' => request()->user()->id,
                    'updated_at' => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("SubscribeSettingService (createOrUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

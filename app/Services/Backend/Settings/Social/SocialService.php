<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Social;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\Social\SocialInterface;
use App\Models\SocialSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SocialService implements SocialInterface
{
    /**
     * @return SocialSetting|null
     */
    public function getModel(): ?SocialSetting
    {
        return SocialSetting::query()->first();
    }

    /**
     * @param int $id
     *
     * @return SocialSetting|null
     */
    public function getSocialById(int $id): ?SocialSetting
    {
        return SocialSetting::query()->where('id', $id)->first();
    }



    /**
     * @param BaseDTOInterface $socialUpdateDTO
     *
     * @return SocialSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $socialUpdateDTO): ?SocialSetting
    {
        try {
            return SocialSetting::query()->updateOrCreate(
                ['setting_id' => $socialUpdateDTO->settingId],
                [
                    'facebook'   => $socialUpdateDTO?->facebook,
                    'instagram'  => $socialUpdateDTO?->instagram,
                    'linkedin'   => $socialUpdateDTO?->linkedin,
                    'pinterest'  => $socialUpdateDTO?->pinterest,
                    'twitter'    => $socialUpdateDTO?->twitter,
                    'whatsapp'   => $socialUpdateDTO?->whatsapp,
                    'youtube'    => $socialUpdateDTO?->youtube,
                    'updated_by' => request()->user()->id,
                    'updated_at' => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("SocialService (createOrUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

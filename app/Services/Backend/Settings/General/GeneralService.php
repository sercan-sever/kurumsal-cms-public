<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\General;

use App\DTO\Backend\Settings\General\GeneralUpdateDTO;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\General\GeneralInterface;
use App\Models\GeneralSetting;
use App\Models\GeneralSettingContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GeneralService implements GeneralInterface
{
    /**
     * @return GeneralSetting|null
     */
    public function getModel(): ?GeneralSetting
    {
        return GeneralSetting::query()->with('content')->first();
    }

    /**
     * @return GeneralSetting|null
     */
    public function getGeneralAllContent(): ?GeneralSetting
    {
        return GeneralSetting::query()->with('allContent')->first();
    }

    /**
     * @param int $id
     *
     * @return GeneralSetting|null
     */
    public function getGeneralById(int $id): ?GeneralSetting
    {
        return GeneralSetting::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param GeneralUpdateDTO $generalUpdateDTO
     *
     * @return GeneralSetting|null
     */
    public function createOrFirst(GeneralUpdateDTO $generalUpdateDTO): ?GeneralSetting
    {
        try {
            return GeneralSetting::query()->updateOrCreate(
                [
                    'setting_id' => $generalUpdateDTO->settingId
                ],
                [
                    'updated_by'         => request()->user()->id,
                    'updated_at'         => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("GeneralService (createGeneralSetting) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param BaseDTOInterface $generalUpdateDTO
     *
     * @return GeneralSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $generalUpdateDTO): ?GeneralSetting
    {
        try {
            $general = $this->createOrFirst(generalUpdateDTO: $generalUpdateDTO);
            $languages = $generalUpdateDTO->languages;
            $activeLanguages = request('languages', collect());

            foreach ($activeLanguages as $lang) {
                GeneralSettingContent::query()->updateOrCreate(
                    [
                        'general_setting_id' => $general->id,
                        'language_id'        => $lang->id,
                    ],
                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'meta_keywords'     => $languages[$lang?->code]['meta_keywords'] ?? null,
                        'meta_descriptions' => $languages[$lang?->code]['meta_descriptions'] ?? null,
                    ]
                );
            }

            return $general;
        } catch (\Exception $exception) {
            Log::error("GeneralService (createOrUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

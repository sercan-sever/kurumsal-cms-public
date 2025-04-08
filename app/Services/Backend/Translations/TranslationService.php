<?php

declare(strict_types=1);

namespace App\Services\Backend\Translations;

use App\Enums\Jobs\JobStatusEnum;
use App\Interfaces\Translation\TranslationInterface;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationService implements TranslationInterface
{
    /**
     * @param int $languageId
     *
     * @return Translation|null
     */
    public function createTranslation(int $languageId): ?Translation
    {
        try {
            return Translation::query()->create([
                'language_id' => $languageId,
                'status'      => JobStatusEnum::PROCESSING->value
            ]);
        } catch (\Exception $exception) {
            Log::error("TranslationService (createTranslation) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $translationId
     * @param string $status
     *
     * @return bool
     */
    public function changeStatusTranslation(int $translationId, string $status): bool
    {
        try {
            return (bool)Translation::query()
                ->where('id', $translationId)
                ->update([
                    'status'     => $status,
                    'updated_at' => Carbon::now(),
                ]);
        } catch (\Exception $exception) {
            Log::error("TranslationService (changeStatusTranslation) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param int $languageId
     *
     * @return Translation|null
     */
    public function readStatusComplatedByLanguageId(int $languageId): ?Translation
    {
        return Cache::remember('translation_' . $languageId, 3600, function () use ($languageId) { // 1 saat boyunca cacheleme yapıyor.
            return Translation::query()
                ->with('language', 'contents')
                ->where('language_id', $languageId)
                ->where('status', JobStatusEnum::COMPLETED->value)->first();
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Backend\Translations;

use App\DTO\Backend\Translation\TranslationContentCreateDTO;
use App\DTO\Backend\Translation\TranslationContentUpdateDTO;
use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Translation\TranslationContentInterface;
use App\Models\TranslationContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TranslationContentService implements TranslationContentInterface
{
    /**
     * @param int $translationId
     * @param string $lang
     *
     * @return bool
     */
    public function importDefaultLangTranslationContent(int $translationId, string $lang = 'en'): bool
    {
        try {
            $langPath = lang_path($lang);
            $files = glob("$langPath/*.php");

            $translationsToInsert = [];

            $now = Carbon::now();

            foreach ($files as $file) {
                $group = basename($file, '.php');
                $translations = include $file;

                if (!is_array($translations)) {
                    continue;
                }

                foreach ($translations as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $index => $valueVal) {
                            $translationsToInsert[] = [
                                'translation_id' => $translationId,
                                'group'          => $group,
                                'key'            => $key . '.' . $index,
                                'value'          => is_array($valueVal) ? implode(', ', $valueVal) : $valueVal,
                                'default'        => StatusEnum::ACTIVE->value,
                                'created_at'     => $now,
                                'updated_at'     => $now,
                                'created_by' => optional(request()->user())->id,
                            ];
                        }
                        continue;
                    } else {
                        $translationsToInsert[] = [
                            'translation_id' => $translationId,
                            'group'          => $group,
                            'key'            => $key,
                            'value'          => $value,
                            'default'        => StatusEnum::ACTIVE->value,
                            'created_at'     => $now,
                            'updated_at'     => $now,
                            'created_by' => optional(request()->user())->id,
                        ];
                    }
                }
            }

            // Toplu ekleme işlemi
            return TranslationContent::query()->insert($translationsToInsert);
        } catch (\Exception $exception) {
            Log::error("TranslationContentService (importDefaultLangTranslationContent) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param TranslationContentCreateDTO $translationContentCreateDTO
     *
     * @return TranslationContent|null
     */
    public function createTranslationContent(TranslationContentCreateDTO $translationContentCreateDTO): ?TranslationContent
    {
        try {
            return TranslationContent::query()->create([
                'translation_id' => $translationContentCreateDTO->translation_id,
                'group'          => $translationContentCreateDTO->group,
                'key'            => $translationContentCreateDTO->key,
                'value'          => $translationContentCreateDTO->value,
                'default'        => StatusEnum::PASSIVE->value,
                'created_by'     => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("TranslationContentService (createTranslationContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return TranslationContent|null
     */
    public function readTranslationContent(int $id): ?TranslationContent
    {
        $content = TranslationContent::query()->with('translation')->where('id', $id)->first();

        if (empty($content) || !($content?->translation?->isCompleted())) {
            return null;
        }

        return $content;
    }


    /**
     * @param TranslationContentUpdateDTO $translationContentUpdateDTO
     *
     * @return TranslationContent|null
     */
    public function updateTranslationContent(TranslationContentUpdateDTO $translationContentUpdateDTO): ?TranslationContent
    {
        try {
            $content = $this->readTranslationContent(id: $translationContentUpdateDTO->id);

            if (empty($content->id)) {
                return null;
            }

            $result = $content->update([
                'group'      => $translationContentUpdateDTO->group,
                'key'        => $translationContentUpdateDTO->key,
                'value'      => $translationContentUpdateDTO->value,
                'updated_by' => request()->user()->id,
            ]);

            return $result ? $content : null;
        } catch (\Exception $exception) {
            Log::error("TranslationContentService (updateTranslationContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return TranslationContent|null
     */
    public function deleteTranslationContent(int $id): ?TranslationContent
    {
        try {
            $content = $this->readTranslationContent(id: $id);

            if (empty($content) || !$content->isDeletable()) {
                return null;
            }

            return $content->delete() ? $content : null;
        } catch (\Exception $exception) {
            Log::error("TranslationContentService (deleteTranslationContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

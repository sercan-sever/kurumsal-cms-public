<?php

declare(strict_types=1);

namespace App\Services\Backend\Faq;

use App\Interfaces\Faq\FaqContentInterface;
use App\Models\Faq;
use App\Models\FaqContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FaqContentService implements FaqContentInterface
{
    /**
     * @param Model $faq
     * @param array $languages
     *
     * @return Faq|null
     */
    public function updateOrCreateContent(Model $faq, array $languages): ?Faq
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                FaqContent::query()->updateOrCreate(
                    [
                        'faq_id'    => $faq->id,
                        'language_id'  => $lang->id,
                    ],
                    [
                        'title'        => $languages[$lang?->code]['title'] ?? null,
                        'slug'         => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'description'  => $languages[$lang?->code]['description'] ?? null,
                        'button_title' => $languages[$lang?->code]['button_title'] ?? null,
                        'url'          => $languages[$lang?->code]['url'] ?? null,
                    ]
                );
            }

            $faq->load(['content']);

            return $faq;
        } catch (\Exception $exception) {
            Log::error("FaqContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

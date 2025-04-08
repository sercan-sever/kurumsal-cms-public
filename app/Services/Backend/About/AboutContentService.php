<?php

declare(strict_types=1);

namespace App\Services\Backend\About;

use App\Interfaces\About\AboutContentInterface;
use App\Models\About;
use App\Models\AboutContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AboutContentService implements AboutContentInterface
{
    /**
     * @param Model $about
     * @param array $languages
     *
     * @return Service|null
     */
    public function updateOrCreateContent(Model $about, array $languages): ?About
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                AboutContent::query()->updateOrCreate(
                    [
                        'about_id'    => $about->id,
                        'language_id' => $lang->id,
                    ],
                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'slug'              => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'short_description' => $languages[$lang?->code]['short_description'] ?? null,
                        'description'       => $languages[$lang?->code]['description'] ?? null,
                        'mission'           => $languages[$lang?->code]['mission'] ?? null,
                        'vision'            => $languages[$lang?->code]['vision'] ?? null,
                    ]
                );
            }

            $about->load(['content']);

            return $about;
        } catch (\Exception $exception) {
            Log::error("AboutContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Backend\Reference;

use App\Interfaces\Reference\ReferenceContentInterface;
use App\Models\Reference;
use App\Models\ReferenceContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReferenceContentService implements ReferenceContentInterface
{
    /**
     * @param Model $reference
     * @param array $languages
     *
     * @return Reference|null
     */
    public function updateOrCreateContent(Model $reference, array $languages): ?Reference
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                ReferenceContent::query()->updateOrCreate(
                    [
                        'reference_id' => $reference->id,
                        'language_id'  => $lang->id,
                    ],
                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'slug'              => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'short_description' => $languages[$lang?->code]['short_description'] ?? null,
                        'description'       => $languages[$lang?->code]['description'] ?? null,
                        'meta_keywords'     => $languages[$lang?->code]['meta_keywords'] ?? null,
                        'meta_descriptions' => $languages[$lang?->code]['meta_descriptions'] ?? null,
                    ]
                );
            }

            $reference->load(['content']);

            return $reference;
        } catch (\Exception $exception) {
            Log::error("ReferenceContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

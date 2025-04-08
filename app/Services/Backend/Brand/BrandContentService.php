<?php

declare(strict_types=1);

namespace App\Services\Backend\Brand;

use App\Interfaces\Brand\BrandContentInterface;
use App\Models\Brand;
use App\Models\BrandContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BrandContentService implements BrandContentInterface
{
    /**
     * @param Model $brand
     * @param array $languages
     *
     * @return Brand|null
     */
    public function updateOrCreateContent(Model $brand, array $languages): ?Brand
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BrandContent::query()->updateOrCreate(
                    [
                        'brand_id'    => $brand->id,
                        'language_id' => $lang->id,
                    ],
                    [
                        'title'       => $languages[$lang?->code]['title'] ?? null,
                        'slug'        => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'description' => $languages[$lang?->code]['description'] ?? null,
                    ]
                );
            }

            $brand->load(['content']);

            return $brand;
        } catch (\Exception $exception) {
            Log::error("BrandContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

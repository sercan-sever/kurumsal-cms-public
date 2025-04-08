<?php

declare(strict_types=1);

namespace App\Services\Backend\Banner;

use App\Interfaces\Banner\BannerContentInterface;
use App\Models\Banner;
use App\Models\BannerContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BannerContentService implements BannerContentInterface
{
    /**
     * @param Model $banner
     * @param array $languages
     *
     * @return Banner|null
     */
    public function updateOrCreateContent(Model $banner, array $languages): ?Banner
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BannerContent::query()->updateOrCreate(
                    [
                        'banner_id'    => $banner->id,
                        'language_id'  => $lang->id,
                    ],
                    [
                        'title'        => $languages[$lang?->code]['title'] ?? null,
                        'description'  => $languages[$lang?->code]['description'] ?? null,
                        'button_title' => $languages[$lang?->code]['button_title'] ?? null,
                        'url'          => $languages[$lang?->code]['url'] ?? null,
                    ]
                );
            }

            $banner->load(['content']);

            return $banner;
        } catch (\Exception $exception) {
            Log::error("BannerContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

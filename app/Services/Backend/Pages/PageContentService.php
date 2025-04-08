<?php

declare(strict_types=1);

namespace App\Services\Backend\Pages;

use App\Interfaces\Sections\SectionContentInterface;
use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PageContentService implements SectionContentInterface
{
    /**
     * @param Model $page
     * @param array $languages
     *
     * @return Section|null
     */
    public function updateOrCreateContent(Model $page, array $languages): ?Page
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                PageContent::query()->updateOrCreate(
                    [
                        'page_id'           => $page->id,
                        'language_id'       => $lang->id,
                    ],
                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'slug'              => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'meta_keywords'     => $languages[$lang?->code]['meta_keywords'] ?? null,
                        'meta_descriptions' => $languages[$lang?->code]['meta_descriptions'] ?? null,
                    ]
                );
            }

            $page->load(['content']);

            return $page;
        } catch (\Exception $exception) {
            Log::error("PageContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

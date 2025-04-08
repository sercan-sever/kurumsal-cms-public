<?php

declare(strict_types=1);

namespace App\Services\Backend\Pages;

use App\Models\Page;
use App\Models\PageSectionPage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PageSectionPageService
{
    /**
     * @param int $pageId
     *
     * @return Collection
     */
    public function getAllModel(int $pageId): Collection
    {
        return PageSectionPage::query()
            ->with('section')
            ->where('page_id', $pageId)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param Model $page
     * @param array $sectionIds
     *
     * @return Page|null
     */
    public function updateOrCreateContent(Model $page, array $sectionIds): ?Page
    {
        try {
            /*  $page->sections()->sync($sections); */

            $syncData = [];

            foreach ($sectionIds as $index => $sectionId) {
                $syncData[$sectionId] = ['sorting' => $index + 1];
            }

            $page->sections()->sync($syncData);
            $page->load(['sections']);

            return $page;
        } catch (\Exception $exception) {
            Log::error("PageSectionPageService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

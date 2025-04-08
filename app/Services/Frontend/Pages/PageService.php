<?php

declare(strict_types=1);

namespace App\Services\Frontend\Pages;

use App\Enums\Defaults\StatusEnum;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class PageService
{
    /**
     * @return Collection
     */
    public function getAllActivePage(): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Page|null
     */
    public function getActivePage(): ?Page
    {
        return Page::query()
            ->with(['content', 'sectionActives.content', 'sectionActives.page'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->first();
    }

    /**
     * @param string $slug
     *
     * @return Page|null
     */
    public function getActivePageDetail(string $slug): ?Page
    {
        return Page::query()
            ->with(['content', 'sectionActives.content', 'sectionActives.page'])
            ->where('top_page', null)
            ->where('status', StatusEnum::ACTIVE)
            ->whereHas('content', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();
    }

    /**
     * @param string $subSlug
     * @param string $slug
     *
     * @return Page|null
     */
    public function getActivePageSubDetail(string $subSlug): ?Page
    {
        return Page::query()
            ->with('topPage.content')
            ->where('status', StatusEnum::ACTIVE)
            ->whereHas('content', function ($query) use ($subSlug) {
                $query->where('slug', $subSlug);
            })
            ->first();
    }
}

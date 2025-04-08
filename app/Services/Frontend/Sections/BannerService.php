<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BannerService
{
    /**
     * @return Collection
     */
    public function getActiveBanners(): Collection
    {
        return Banner::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function banner(Request $request, Page $page, Section $section)
    {
        $banners =  $this->getActiveBanners();

        return view('components.frontend.sections.banners.banner', compact('page', 'section', 'banners'))->render();
    }
}

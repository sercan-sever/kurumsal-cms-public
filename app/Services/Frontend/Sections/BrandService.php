<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Brand;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getBrands(): Collection
    {
        return Brand::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'desc')->get();
    }


    /**
     * @param int $limit
     *
     * @return LengthAwarePaginator
     */
    public function getBrandPaginate(int $limit = 9): LengthAwarePaginator
    {
        return Brand::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'desc')->paginate(perPage: $limit);
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function brand(Request $request, Page $page, Section $section)
    {
        $brands =  $this->getBrandPaginate();

        return view('components.frontend.sections.brands.brand', compact('page', 'section', 'brands'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function brandSlider(Request $request, Page $page, Section $section)
    {
        $brands =  $this->getBrands();

        return view('components.frontend.sections.brands.brand-slider', compact('page', 'section', 'brands'))->render();
    }
}

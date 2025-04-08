<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Page;
use App\Models\Section;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReferenceService
{
    /**
     * @return Collection
     */
    public function getReferences(): Collection
    {
        return Reference::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->limit(value: 6)->get();
    }

    /**
     * @return Collection
     */
    public function getLatestReferences(): Collection
    {
        return Reference::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'desc')->limit(value: 4)->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getReferencePaginate(): LengthAwarePaginator
    {
        return Reference::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->paginate(perPage: 9);
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function reference(Request $request, Page $page, Section $section)
    {
        $references =  $this->getReferencePaginate();

        return view('components.frontend.sections.references.reference', compact('page', 'section', 'references'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function referenceGrid(Request $request, Page $page, Section $section)
    {
        $references =  $this->getLatestReferences();

        return view('components.frontend.sections.references.reference-grid', compact('page', 'section', 'references'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function referenceSlider(Request $request, Page $page, Section $section)
    {
        $references =  $this->getReferences();

        return view('components.frontend.sections.references.reference-slider', compact('page', 'section', 'references'))->render();
    }
}

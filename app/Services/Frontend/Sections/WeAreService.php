<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\About;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class WeAreService
{
    /**
     * @return About|null
     */
    public function getWeare(): ?About
    {
        return About::query()->with('content')->first();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function weAre(Request $request, Page $page, Section $section)
    {
        $weAre =  $this->getWeare();

        return view('components.frontend.sections.weares.weare', compact('page', 'section', 'weAre'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function weAreTwo(Request $request, Page $page, Section $section)
    {
        $weAre =  $this->getWeare();

        return view('components.frontend.sections.weares.weare-two', compact('page', 'section', 'weAre'))->render();
    }
}

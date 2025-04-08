<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class DynamicService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function dynamic(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.dynamics.dynamic', compact('page', 'section'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function dynamicImage(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.dynamics.image-dynamic', compact('page', 'section'))->render();
    }

    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function dynamicRight(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.dynamics.right-dynamic', compact('page', 'section'))->render();
    }

    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function dynamicLeft(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.dynamics.left-dynamic', compact('page', 'section'))->render();
    }
}

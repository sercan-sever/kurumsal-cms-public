<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class FooterService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function footer(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.footers.footer', compact('page', 'section'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function footerTwo(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.footers.footer-two', compact('page', 'section'))->render();
    }
}

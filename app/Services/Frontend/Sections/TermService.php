<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class TermService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function term(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.terms.term', compact('page', 'section'))->render();
    }
}

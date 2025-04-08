<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class ContactFormService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function form(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.contact-forms.form', compact('page', 'section'))->render();
    }
}

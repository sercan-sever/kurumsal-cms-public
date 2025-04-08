<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class ContactService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function contact(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.contacts.contact', compact('page', 'section'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function contactTwo(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.contacts.contact-two', compact('page', 'section'))->render();
    }
}

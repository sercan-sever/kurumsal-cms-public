<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class PrivacyPolicyService
{
    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function privacyPolicy(Request $request, Page $page, Section $section)
    {
        return view('components.frontend.sections.privacy-policies.privacy-policy', compact('page', 'section'))->render();
    }
}

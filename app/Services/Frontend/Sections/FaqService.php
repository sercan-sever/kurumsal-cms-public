<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FaqService
{
    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getFaqs(): Collection
    {
        return Faq::query()
            ->with('content')
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
    public function faq(Request $request, Page $page, Section $section)
    {
        $faqs =  $this->getFaqs();

        return view('components.frontend.sections.faqs.faq', compact('page', 'section', 'faqs'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function faqTwo(Request $request, Page $page, Section $section)
    {
        $faqs =  $this->getFaqs();

        return view('components.frontend.sections.faqs.faq-two', compact('page', 'section', 'faqs'))->render();
    }
}

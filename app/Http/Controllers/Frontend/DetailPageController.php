<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\Pages\Section\PageSectionEnum;
use App\Http\Controllers\Controller;
use App\Services\Frontend\Pages\PageService as FrontendPageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DetailPageController extends Controller
{
    /**
     * @param FrontendPageService $pageService
     *
     * @return void
     */
    public function __construct(
        private readonly FrontendPageService $pageService
    ) {
        //
    }

    /**
     * @param Request $request
     * @param string|null $lang
     * @param string $slug
     *
     * @return View
     */
    public function detail(Request $request, ?string $lang, string $slug): View
    {
        try {
            if (empty($lang)) {
                throw new \Exception("Sayfa Bulunamadı !!", 404);
            }

            $page = $this->pageService->getActivePageDetail(slug: $slug);

            if (empty($page) || empty($page?->content)) {
                throw new \Exception("Sayfa Bulunamadı !!", 404);
            }

            $renderedSections = $page?->sectionActives->map(function ($section) use ($request, $page) {
                return PageSectionEnum::getSectionFrontendView(request: $request, page: $page, section: $section);
            })->implode('');


            return view('frontend.pages.detail', compact('page', 'renderedSections'));
        } catch (\Throwable $exception) {
            abort(404);
        }
    }
}

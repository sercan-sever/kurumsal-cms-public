<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\Pages\Section\PageSectionEnum;
use App\Http\Controllers\Controller;
use App\Services\Frontend\Pages\PageService as FrontendPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomePageController extends Controller
{
    /**
     * @param FrontendPageService $pageService
     *
     * @return void
     */
    public function __construct(
        private readonly FrontendPageService $pageService,
    ) {
        //
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function home(Request $request): View
    {
        try {
            $page = $this->pageService->getActivePage();

            if (empty($page)) {
                abort(404);
            }

            $renderedSections = $page?->sectionActives->map(function ($section) use ($request, $page) {
                return PageSectionEnum::getSectionFrontendView(request: $request, page: $page, section: $section);
            })->implode(''); // Tüm section'ları string olarak birleştir


            return view('frontend.pages.home', compact('page', 'renderedSections'));
        } catch (\Exception $exception) {
            Log::error("HomePageController (home) : ", context: [$exception->getMessage()]);

            abort(404);
        }
    }
}

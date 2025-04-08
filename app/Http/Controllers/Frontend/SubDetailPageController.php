<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\Pages\Page\SubPageDesignEnum;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\Backend\Sections\SectionService;
use App\Services\Frontend\Blogs\BlogService;
use App\Services\Frontend\Pages\PageService as FrontendPageService;
use App\Services\Frontend\Services\ServiceManager;
use App\Services\Frontend\References\ReferenceService;
use Illuminate\View\View;

class SubDetailPageController extends Controller
{
    /**
     * @param FrontendPageService $pageService
     * @param SectionService $sectionService
     * @param ServiceManager $serviceManager
     * @param ReferenceService $referenceService
     * @param BlogService $blogService
     *
     * @return void
     */
    public function __construct(
        private readonly FrontendPageService $pageService,
        private readonly SectionService $sectionService,
        private readonly ServiceManager $serviceManager,
        private readonly ReferenceService $referenceService,
        private readonly BlogService $blogService,
    ) {
        //
    }

    /**
     * @param string|null $lang
     * @param string $subSlug
     * @param string $slug
     *
     * @return View
     */
    public function subDetail(?string $lang, string $subSlug, string $slug): View
    {
        try {
            if (empty($lang)) {
                throw new \Exception("Sayfa Bulunamadı !!", 404);
            }

            $page = $this->pageService->getActivePageSubDetail(subSlug: $subSlug);

            if (empty($page) || empty($page?->content)) {
                throw new \Exception("Sayfa Bulunamadı !!", 404);
            }

            return $this->designCheck(page: $page, subSlug: $subSlug, slug: $slug);
        } catch (\Throwable $exception) {
            abort(404);
        }
    }

    /**
     * @param Page $page
     * @param string $subSlug
     * @param string $slug
     *
     * @return  View
     */
    private function designCheck(Page $page, string $subSlug, string $slug): View
    {
        return match ($page?->design?->value) {
            SubPageDesignEnum::SERVICE->value   => $this->service(page: $page, subSlug: $subSlug, slug: $slug),
            SubPageDesignEnum::REFERENCE->value => $this->reference(page: $page, subSlug: $subSlug, slug: $slug),
            SubPageDesignEnum::BLOG->value      => $this->blog(page: $page, subSlug: $subSlug, slug: $slug),
            SubPageDesignEnum::NONE->value      => abort(404),

            default => abort(404),
        };
    }


    /**
     * @param Page $page
     * @param string $subSlug
     * @param string $slug
     *
     * @return  View
     */
    private function service(Page $page, string $subSlug, string $slug): View
    {
        $service = $this->serviceManager->getServiceDetail(slug: $slug);
        $section = $this->sectionService->getContactForm();

        if (empty($service->id)) {
            abort(404);
        }

        $services = $this->serviceManager->getServices();

        return view('frontend.pages.sub-details.service', compact('page', 'service', 'services', 'section'));
    }


    /**
     * @param Page $page
     * @param string $subSlug
     * @param string $slug
     *
     * @return  View
     */
    private function reference(Page $page, string $subSlug, string $slug): View
    {
        $reference = $this->referenceService->getReferenceDetail(slug: $slug);
        $section   = $this->sectionService->getContactForm();

        if (empty($reference->id)) {
            abort(404);
        }

        return view('frontend.pages.sub-details.reference', compact('page', 'reference', 'section'));
    }


    /**
     * @param Page $page
     * @param string $subSlug
     * @param string $slug
     *
     * @return  View
     */
    private function blog(Page $page, string $subSlug, string $slug): View
    {
        $blog = $this->blogService->getBlogDetail(slug: $slug);

        if (empty($blog->id)) {
            abort(404);
        }

        $blogs = $this->blogService->getLatestBlogs(slug: $slug);

        return view('frontend.pages.sub-details.blog', compact('page', 'blog', 'blogs'));
    }
}

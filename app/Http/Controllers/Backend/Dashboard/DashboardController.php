<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Backend\Blogs\Blog\BlogService;
use App\Services\Backend\Blogs\Subscribe\BlogSubscribeService;
use App\Services\Backend\Pages\PageService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * @param BlogService $blogService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogService $blogService,
        private readonly PageService $pageService,
        private readonly BlogSubscribeService $blogSubscribeService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        // PAGE
        $activePageCount  = $this->pageService->getActiveModelCount();
        $passivePageCount = $this->pageService->getPassiveModelCount();
        $deletedPageCount = $this->pageService->getAllDeletedModelCount();

        // BLOG
        $blogs            = $this->blogService->getAllActiveModelByPublishedAt(limit: 10);
        $activeBlogCount  = $this->blogService->getActiveModelCount();
        $passiveBlogCount = $this->blogService->getPassiveModelCount();
        $deletedBlogCount = $this->blogService->getAllDeletedModelCount();

        // SUBSCRIBE
        $activeSubscribeCount  = $this->blogSubscribeService->getActiveModelCount();
        $passiveSubscribeCount = $this->blogSubscribeService->getPassiveModelCount();
        $deletedSubscribeCount = $this->blogSubscribeService->getAllDeletedModelCount();

        return view(
            'backend.modules.dashboard.dashboard',
            compact(
                'activePageCount',
                'passivePageCount',
                'deletedPageCount',

                'blogs',
                'activeBlogCount',
                'passiveBlogCount',
                'deletedBlogCount',

                'activeSubscribeCount',
                'passiveSubscribeCount',
                'deletedSubscribeCount'
            )
        );
    }
}

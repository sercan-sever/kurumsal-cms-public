<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sitemap;

use App\Models\Page;

use App\Enums\Pages\Page\SubPageDesignEnum;

use App\Services\Backend\Language\LanguageService;

use App\Services\Frontend\Blogs\BlogService;
use App\Services\Frontend\Pages\PageService;
use App\Services\Frontend\References\ReferenceService;
use App\Services\Frontend\Services\ServiceManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    /**
     * @param LanguageService $languageService
     * @param PageService $pageService
     * @param BlogService $blogService
     * @param ServiceManager $serviceManager
     * @param ReferenceService $referenceService
     *
     * @return void
     */
    public function __construct(
        private readonly LanguageService $languageService,
        private readonly PageService $pageService,
        private readonly BlogService $blogService,
        private readonly ServiceManager $serviceManager,
        private readonly ReferenceService $referenceService,
    ) {
        //
    }

    /**
     * @return void
     */
    public function generateSitemap(): void
    {
        try {
            $sitemap = Sitemap::create();

            $sitemap = $this->language(sitemap: $sitemap);

            $sitemap = $this->page(sitemap: $sitemap);

            $sitemap->writeToFile(public_path('sitemap.xml'));
        } catch (\Exception $exception) {
            Log::error(message: 'SitemapService ( generateSitemap ) Bir Hata Oluştu !!!', context: [$exception->getMessage()]);
        }
    }


    /**
     * @param Sitemap $sitemap
     *
     * @return Sitemap
     */
    private function language(Sitemap $sitemap): Sitemap
    {
        // Language
        $languages = $this->languageService->getAllActiveStatusLanguages();

        if ($languages->isNotEmpty()) {
            foreach ($languages as $lang) {
                $sitemap->add(
                    Url::create('/' . $lang?->code)->setLastModificationDate(Carbon::now())
                );
            }
        }

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     *
     * @return Sitemap
     */
    private function page(Sitemap $sitemap): Sitemap
    {
        // Top Pages
        $pages = $this->pageService->getAllActivePage();

        if ($pages->isNotEmpty()) {
            foreach ($pages as $page) {
                if ($page?->design?->value == SubPageDesignEnum::NONE->value && is_null($page?->top_page)) {
                    $this->pageTop(sitemap: $sitemap, contents: $page?->allContent);
                } else {
                    $this->pageSub(sitemap: $sitemap, page: $page, contents: $page?->allContent);
                }
            }
        }

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     * @param Collection|null $contents
     *
     * @return Sitemap
     */
    private function pageTop(Sitemap $sitemap, ?Collection $contents): Sitemap
    {
        if ($contents?->isNotEmpty()) {
            foreach ($contents as $content) {
                $sitemap->add(
                    Url::create('/' . $content?->language?->code . '/' . $content?->slug)->setLastModificationDate(Carbon::now())
                );
            }
        }

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     * @param Page $page
     * @param Collection|null $contents
     *
     * @return Sitemap
     */
    private function pageSub(Sitemap $sitemap, Page $page, ?Collection $contents): Sitemap
    {
        match ($page?->design?->value) {
            SubPageDesignEnum::BLOG->value => $this->blog(sitemap: $sitemap, contents: $contents),
            SubPageDesignEnum::SERVICE->value => $this->service(sitemap: $sitemap, contents: $contents),
            SubPageDesignEnum::REFERENCE->value => $this->reference(sitemap: $sitemap, contents: $contents),

            default => '',
        };

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     * @param Collection|null $contents
     *
     * @return Sitemap
     */
    private function blog(Sitemap $sitemap, ?Collection $contents): Sitemap
    {
        $blogs = $this->blogService->getAllActiveModel();

        if ($blogs->isNotEmpty() && $contents->isNotEmpty()) {
            foreach ($contents as $pageContent) {
                $sitemap->add(
                    Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug)->setLastModificationDate(Carbon::now()),
                );

                foreach ($blogs as $blog) {
                    foreach ($blog?->allContent as $blogContent) {

                        if ($pageContent?->language_id != $blogContent?->language_id) {
                            continue;
                        }

                        $sitemap->add(
                            Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug . '/' . $blogContent?->slug)->setLastModificationDate(Carbon::now()),
                        );
                    }
                }
            }
        }

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     * @param Collection|null $contents
     *
     * @return Sitemap
     */
    private function service(Sitemap $sitemap, ?Collection $contents): Sitemap
    {
        $services = $this->serviceManager->getServices();

        if ($services->isNotEmpty() && $contents->isNotEmpty()) {
            foreach ($contents as $pageContent) {

                $sitemap->add(
                    Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug)->setLastModificationDate(Carbon::now()),
                );

                foreach ($services as $service) {
                    foreach ($service?->allContent as $serviceContent) {

                        if ($pageContent?->language_id != $serviceContent?->language_id) {
                            continue;
                        }

                        $sitemap->add(
                            Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug . '/' . $serviceContent?->slug)->setLastModificationDate(Carbon::now()),
                        );
                    }
                }
            }
        }

        return $sitemap;
    }


    /**
     * @param Sitemap $sitemap
     * @param Collection|null $contents
     *
     * @return Sitemap
     */
    private function reference(Sitemap $sitemap, ?Collection $contents): Sitemap
    {
        $references = $this->referenceService->getAllModel();

        if ($references->isNotEmpty() && $contents->isNotEmpty()) {
            foreach ($contents as $pageContent) {
                foreach ($references as $reference) {
                    $sitemap->add(
                        Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug)->setLastModificationDate(Carbon::now()),
                    );

                    foreach ($reference?->allContent as $referenceContent) {

                        if ($pageContent?->language_id != $referenceContent?->language_id) {
                            continue;
                        }

                        $sitemap->add(
                            Url::create('/' . $pageContent?->language?->code . '/' . $pageContent?->slug . '/' . $referenceContent?->slug)->setLastModificationDate(Carbon::now()),
                        );
                    }
                }
            }
        }

        return $sitemap;
    }
}

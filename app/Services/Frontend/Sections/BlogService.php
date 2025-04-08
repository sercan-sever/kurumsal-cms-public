<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class BlogService
{
    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getLatestBlogs(int $limit = 3): Collection
    {
        return Blog::query()
            ->with(['content'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')->limit(value: $limit)->get();
    }

    /**
     * @param string|null $category
     * @param string|null $tag
     * @param int $limit
     *
     * @return LengthAwarePaginator
     */
    public function getBlogPaginate(?string $category, ?string $tag, int $limit = 9): LengthAwarePaginator
    {
        return Blog::query()
            ->with(['content', 'categories.content', 'tags.content', 'comments'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('published_at', '<=', now())
            // Kategori filtresi ekle
            ->when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->whereHas('content', function ($qc) use ($category) {
                        $qc->where('slug', $category);
                    })->where('status', StatusEnum::ACTIVE);
                });
            })
            // Etiket filtresi ekle
            ->when($tag, function ($query) use ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->whereHas('content', function ($qc) use ($tag) {
                        $qc->where('slug', $tag);
                    })->where('status', StatusEnum::ACTIVE);
                });
            })
            ->orderBy('published_at', 'desc')->paginate(perPage: $limit);
    }

    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function blog(Request $request, Page $page, Section $section)
    {
        // 1. Gelen GET parametrelerini doğrula
        $valid = Validator::make($request->all(), [
            'category' => [
                'nullable',
                'string',
                'min:1',
                /* Rule::exists('blog_category_contents', 'slug')->where('language_id', $request?->siteLangID ?? null), */
            ],
            'tag' => [
                'nullable',
                'string',
                'min:1',
                /*  Rule::exists('blog_tag_contents', 'slug')->where('language_id', $request?->siteLangID ?? null), */
            ],
        ])->validated();

        // 2. Filtreleme için değişkenlere ata
        $categorySlug = $valid['category'] ?? null;
        $tagSlug = $valid['tag'] ?? null;

        $blogs =  $this->getBlogPaginate(limit: $section?->limit ?? 9, category: $categorySlug, tag: $tagSlug);

        return view('components.frontend.sections.blogs.blog', compact('page', 'section', 'blogs'))->render();
    }

    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function latestBlogs(Request $request, Page $page, Section $section)
    {
        $blogs =  $this->getLatestBlogs(limit: $section?->limit ?? 3);

        return view('components.frontend.sections.blogs.blog-latest', compact('page', 'section', 'blogs'))->render();
    }
}

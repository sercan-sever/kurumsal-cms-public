<?php

declare(strict_types=1);

namespace App\Services\Frontend\Blogs;

use App\Enums\Defaults\StatusEnum;
use App\Models\Blog;
use App\Models\BlogContent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BlogService
{
    /**
     * @return Collection
     */
    public function getAllPublishedBlogContent(): Collection
    {
        return BlogContent::query()
            ->whereHas('blog', function ($query) {
                $query->where('status', StatusEnum::ACTIVE)
                    ->where('published_at', '<=', Carbon::now())
                    ->where('notified_at', StatusEnum::PASSIVE);
            })->get();
    }


    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return Blog::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc')->get();
    }


    /**
     * @return string $slug
     *
     * @return Blog|null
     */
    public function getBlogDetail(string $slug): ?Blog
    {
        return Blog::query()
            ->with(['categories.content', 'tags.content', 'comments'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('published_at', '<=', Carbon::now())
            ->whereHas('content', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();
    }

    /**
     * @param string $slug
     *
     * @return Collection
     */
    public function getLatestBlogs(string $slug): Collection
    {
        return Blog::query()
            ->where('status', StatusEnum::ACTIVE)
            ->where('published_at', '<=', Carbon::now())
            ->whereHas('content', function ($query) use ($slug) {
                $query->where('slug', '<>', $slug);
            })
            ->orderBy('published_at', 'desc')->limit(value: 3)->get();
    }
}

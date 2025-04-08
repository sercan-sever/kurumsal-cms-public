<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\BlogTagBlog;

use App\Interfaces\Blogs\BlogTagBlog\BlogTagBlogInterface;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogTagBlogService implements BlogTagBlogInterface
{
    /**
     * @param Model $blog
     * @param array $tags
     */
    public function updateOrCreateContent(Model $blog, array $tags): ?Blog
    {
        try {
            $blog->tags()->sync($tags);

            $blog->load(['tags']);

            return $blog;
        } catch (\Exception $exception) {
            Log::error("BlogTagBlogService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

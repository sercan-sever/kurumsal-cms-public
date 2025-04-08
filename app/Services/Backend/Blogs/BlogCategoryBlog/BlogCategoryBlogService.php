<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\BlogCategoryBlog;

use App\Interfaces\Blogs\BlogCategoryBlog\BlogCategoryBlogInterface;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogCategoryBlogService implements BlogCategoryBlogInterface
{
    /**
     * @param Model $blog
     * @param array $categories
     */
    public function updateOrCreateContent(Model $blog, array $categories): ?Blog
    {
        try {
            $blog->categories()->sync($categories);

            $blog->load(['categories']);

            return $blog;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryBlogService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

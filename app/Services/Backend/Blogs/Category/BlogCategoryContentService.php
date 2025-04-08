<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Category;

use App\Interfaces\Blogs\Category\BlogCategoryContentInterface;
use App\Models\BlogCategory;
use App\Models\BlogCategoryContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogCategoryContentService implements BlogCategoryContentInterface
{
    /**
     * @param Model $blogCategory
     * @param array $languages
     *
     * @return BlogCategory|null
     */
    public function updateOrCreateContent(Model $blogCategory, array $languages): ?BlogCategory
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BlogCategoryContent::query()->updateOrCreate(
                    [
                        'blog_category_id' => $blogCategory->id,
                        'language_id'      => $lang->id,
                    ],

                    [
                        'title' => $languages[$lang?->code]['title'] ?? null,
                        'slug'  => str($languages[$lang?->code]['title'] ?? '')->slug(),
                    ]
                );
            }

            $blogCategory->load(['content', 'allContent']);

            return $blogCategory;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryContentService (updateOrCreateBlogCategoryContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

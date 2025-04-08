<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Blog;

use App\Interfaces\Blogs\Blog\BlogContentInterface;
use App\Models\Blog;
use App\Models\BlogContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogContentService implements BlogContentInterface
{
    /**
     * @param Model $blog
     * @param array $languages
     *
     * @return Blog|null
     */
    public function updateOrCreateContent(Model $blog, array $languages): ?Blog
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BlogContent::query()->updateOrCreate(
                    [
                        'blog_id'     => $blog->id,
                        'language_id' => $lang->id,
                    ],

                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'slug'              => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'description'       => $languages[$lang?->code]['description'] ?? null,
                        'meta_keywords'     => $languages[$lang?->code]['meta_keywords'] ?? null,
                        'meta_descriptions' => $languages[$lang?->code]['meta_descriptions'] ?? null,
                    ]
                );
            }

            $blog->load(['content']);

            return $blog;
        } catch (\Exception $exception) {
            Log::error("BlogContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Tag;

use App\Interfaces\Blogs\Tag\BlogTagContentInterface;
use App\Models\BlogTag;
use App\Models\BlogTagContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogTagContentService implements BlogTagContentInterface
{
    /**
     * @param Model $blogTag
     * @param array $languages
     *
     * @return BlogTag|null
     */
    public function updateOrCreateContent(Model $blogTag, array $languages): ?BlogTag
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BlogTagContent::query()->updateOrCreate(
                    [
                        'blog_tag_id' => $blogTag->id,
                        'language_id' => $lang->id,
                    ],

                    [
                        'title' => $languages[$lang?->code]['title'] ?? null,
                        'slug'  => str($languages[$lang?->code]['title'] ?? '')->slug(),
                    ]
                );
            }

            $blogTag->load(['content', 'allContent']);

            return $blogTag;
        } catch (\Exception $exception) {
            Log::error("BlogTagContentService (updateOrCreateBlogTagContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}

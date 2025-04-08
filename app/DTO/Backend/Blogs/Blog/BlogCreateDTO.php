<?php

namespace App\DTO\Backend\Blogs\Blog;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogCreateDTO implements BaseDTOInterface
{
    /**
     * @param UploadedFile $image
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param StatusEnum|string $commentStatus
     * @param string $publishedAt
     * @param array $categories
     * @param array $tags
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly UploadedFile $image,
        public readonly int $sorting,
        public readonly StatusEnum|string $status,
        public readonly StatusEnum|string $commentStatus,
        public readonly string $publishedAt,
        public readonly array $categories,
        public readonly array $tags,
        public readonly array $languages,
    ) {
        //
    }

    /**
     * @param Request $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $valid = $request->validated();

        $image           = $valid['image'];
        $sorting         = $valid['sorting'];
        $publishedAt     = $valid['published_at'];
        $status          = $valid['status'];
        $commentStatus   = $valid['comment_status'];
        $categories      = $valid['categories'];
        $tags            = $valid['tags'];

        unset(
            $valid['image'],
            $valid['sorting'],
            $valid['published_at'],
            $valid['status'],
            $valid['comment_status'],
            $valid['categories'],
            $valid['tags']
        );

        return new self(
            image: $image,
            sorting: $sorting,
            publishedAt: $publishedAt,
            status: $status,
            commentStatus: $commentStatus,
            categories: $categories,
            tags: $tags,
            languages: $valid,
        );
    }
}

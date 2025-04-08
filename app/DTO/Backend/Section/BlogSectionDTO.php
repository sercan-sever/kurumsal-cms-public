<?php

namespace App\DTO\Backend\Section;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogSectionDTO implements BaseDTOInterface
{
    /**
     * @param int $sectionId
     * @param int|null $pageId
     * @param string $title
     * @param int $limit
     * @param int $sorting
     * @param string $status
     * @param UploadedFile|null $image
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $sectionId,
        public readonly int $pageId,
        public readonly string $title,
        public readonly int $limit,
        public readonly int $sorting,
        public readonly string $status,
        public readonly ?UploadedFile $image,
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

        $sectionId = $valid['id'];
        $pageId    = $valid['page'] ?? null;
        $title     = $valid['title'];
        $image     = $valid['image'] ?? null;
        $limit     = $valid['limit'];
        $sorting   = $valid['sorting'];
        $status    = $valid['status'];

        unset(
            $valid['id'],
            $valid['page'],
            $valid['title'],
            $valid['image'],
            $valid['limit'],
            $valid['sorting'],
            $valid['status'],
        );

        return new self(
            sectionId: $sectionId,
            pageId: $pageId,
            title: $title,
            image: $image,
            limit: $limit,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

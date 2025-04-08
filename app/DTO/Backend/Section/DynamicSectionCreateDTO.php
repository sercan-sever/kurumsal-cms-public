<?php

namespace App\DTO\Backend\Section;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class DynamicSectionCreateDTO implements BaseDTOInterface
{
    /**
     * @param int|null $pageId
     * @param string $title
     * @param int $sorting
     * @param string $status
     * @param UploadedFile $image
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly ?int $pageId,
        public readonly string $title,
        public readonly int $sorting,
        public readonly string $status,
        public readonly UploadedFile $image,
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

        $pageId  = $valid['page'] ?? null;
        $title   = $valid['title'];
        $image   = $valid['image'] ?? null;
        $sorting = $valid['sorting'];
        $status  = $valid['status'];

        unset(
            $valid['page'],
            $valid['title'],
            $valid['image'],
            $valid['sorting'],
            $valid['status'],
        );

        return new self(
            pageId: $pageId,
            title: $title,
            image: $image,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

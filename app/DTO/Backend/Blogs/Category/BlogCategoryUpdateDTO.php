<?php

namespace App\DTO\Backend\Blogs\Category;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogCategoryUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $blogCategoryId
     * @param UploadedFile|null $image
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogCategoryId,
        public readonly UploadedFile|null $image,
        public readonly int $sorting,
        public readonly StatusEnum|string $status,
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

        $blogCategoryId = $valid['id'];
        $image          = $valid['image'] ?? null;
        $sorting        = $valid['sorting'];
        $status         = $valid['status'];

        unset($valid['id'], $valid['image'], $valid['sorting'], $valid['status']);

        return new self(
            blogCategoryId: $blogCategoryId,
            image: $image,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

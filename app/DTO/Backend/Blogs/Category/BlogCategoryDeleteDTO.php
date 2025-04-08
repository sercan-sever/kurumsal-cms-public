<?php

namespace App\DTO\Backend\Blogs\Category;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogCategoryDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $blogCategoryId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogCategoryId,
        public readonly string $deletedDescription,
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

        return new self(
            blogCategoryId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

<?php

namespace App\DTO\Backend\Blogs\Blog;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $blogId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogId,
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
            blogId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

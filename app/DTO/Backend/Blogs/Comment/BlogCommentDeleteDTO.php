<?php

namespace App\DTO\Backend\Blogs\Comment;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogCommentDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $blogCommentId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogCommentId,
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
            blogCommentId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

<?php

namespace App\DTO\Backend\Blogs\Comment;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogCommentConfirmDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     * @param string|null $comment
     * @param string|null $replyComment
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
        public readonly string|null $comment,
        public readonly string|null $replyComment,
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
            id: $valid['id'],
            comment: $valid['comment'] ?? null,
            replyComment: $valid['reply_comment'] ?? null,
        );
    }
}

<?php

namespace App\DTO\Backend\Blogs\Comment;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogCommentCreateDTO implements BaseDTOInterface
{
    /**
     * @param string $slug
     * @param string $name
     * @param string $email
     * @param string $comment
     * @param string $ipAddress
     *
     * @return void
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $name,
        public readonly string $email,
        public readonly string $comment,
        public readonly string $ipAddress,
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
        $valid     = $request->validated();
        $ipAddress = $request->ip();

        return new self(
            slug: $valid['slug'],
            name: $valid['name'],
            email: $valid['email'],
            comment: $valid['comment'],
            ipAddress: $ipAddress,
        );
    }
}

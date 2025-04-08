<?php

namespace App\DTO\Backend\Blogs\Tag;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogTagDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $blogTagId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogTagId,
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
            blogTagId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

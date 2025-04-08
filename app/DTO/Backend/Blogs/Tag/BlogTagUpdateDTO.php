<?php

namespace App\DTO\Backend\Blogs\Tag;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogTagUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $blogTagId
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $blogTagId,
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

        $blogTagId = $valid['id'];
        $sorting   = $valid['sorting'];
        $status    = $valid['status'];

        unset($valid['id'], $valid['sorting'], $valid['status']);

        return new self(
            blogTagId: $blogTagId,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

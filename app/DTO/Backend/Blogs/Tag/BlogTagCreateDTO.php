<?php

namespace App\DTO\Backend\Blogs\Tag;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogTagCreateDTO implements BaseDTOInterface
{
    /**
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
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
        $sorting = $valid['sorting'];
        $status  = $valid['status'];

        unset($valid['sorting'], $valid['status']);

        return new self(
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

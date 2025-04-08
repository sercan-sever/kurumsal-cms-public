<?php

namespace App\DTO\Backend\Blogs\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogSubscribeUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $subscribeId
     * @param int $languageId
     * @param string $status
     *
     * @return void
     */
    public function __construct(
        public readonly int $subscribeId,
        public readonly int $languageId,
        public readonly string $status,
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
            subscribeId: $valid['id'],
            languageId: $valid['language'],
            status: $valid['status'],
        );
    }
}

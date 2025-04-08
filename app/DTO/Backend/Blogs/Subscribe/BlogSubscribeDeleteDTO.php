<?php

namespace App\DTO\Backend\Blogs\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogSubscribeDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $subscribeId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $subscribeId,
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
            subscribeId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

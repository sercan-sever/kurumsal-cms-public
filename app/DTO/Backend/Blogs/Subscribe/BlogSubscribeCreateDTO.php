<?php

namespace App\DTO\Backend\Blogs\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BlogSubscribeCreateDTO implements BaseDTOInterface
{
    /**
     * @param int|null $languageId
     * @param string $email
     * @param string $ipAddress
     *
     * @return void
     */
    public function __construct(
        public readonly int|null $languageId,
        public readonly string $email,
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
            languageId: $request?->siteLangID ?? null,
            email: $valid['email'],
            ipAddress: $ipAddress,
        );
    }
}

<?php

namespace App\DTO\Backend\Pages;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class PageDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $pageId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $pageId,
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
            pageId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

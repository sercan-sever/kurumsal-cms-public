<?php

namespace App\DTO\Backend\PageDetail;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class PageDetailUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $pageId
     * @param array $sections
     *
     * @return void
     */
    public function __construct(
        public readonly int $pageId,
        public readonly array $sections,
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
            sections: $valid['sections'],
        );
    }
}

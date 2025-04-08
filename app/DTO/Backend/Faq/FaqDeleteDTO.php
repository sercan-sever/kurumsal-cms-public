<?php

namespace App\DTO\Backend\Faq;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class FaqDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $faqId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $faqId,
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
            faqId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

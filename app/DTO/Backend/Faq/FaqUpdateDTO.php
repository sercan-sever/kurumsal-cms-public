<?php

namespace App\DTO\Backend\Faq;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class FaqUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $faqId
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $faqId,
        public readonly int $sorting,
        public readonly string $status,
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

        $faqId    = $valid['id'];
        $sorting  = $valid['sorting'];
        $status   = $valid['status'];

        unset($valid['id'], $valid['sorting'], $valid['status']);

        return new self(
            faqId: $faqId,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

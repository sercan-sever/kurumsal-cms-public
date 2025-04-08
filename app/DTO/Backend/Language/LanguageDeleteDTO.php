<?php

namespace App\DTO\Backend\Language;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class LanguageDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $languageId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $languageId,
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
            languageId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

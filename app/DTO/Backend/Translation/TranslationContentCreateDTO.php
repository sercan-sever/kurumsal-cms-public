<?php

namespace App\DTO\Backend\Translation;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class TranslationContentCreateDTO implements BaseDTOInterface
{
    /**
     * @param int $translation_id
     * @param string $group
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function __construct(
        public readonly int $translation_id,
        public readonly string $group,
        public readonly string $key,
        public readonly string $value,
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
            translation_id: (int)$valid['translation_id'],
            group: str($valid['group'])->slug(separator: '_')->toString(),
            key: str($valid['key'])->slug(separator: '.')->toString(),
            value: $valid['value'],
        );
    }
}

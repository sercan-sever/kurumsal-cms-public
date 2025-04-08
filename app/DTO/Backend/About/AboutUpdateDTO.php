<?php

namespace App\DTO\Backend\About;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AboutUpdateDTO implements BaseDTOInterface
{
    /**
     * @param UploadedFile|null $image
     * @param UploadedFile|null $otherImage
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly ?UploadedFile $image,
        public readonly ?UploadedFile $otherImage,
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

        $image      = $valid['image'] ?? null;
        $otherImage = $valid['other_image'] ?? null;

        unset(
            $valid['image'],
            $valid['other_image'],
        );

        return new self(
            image: $image,
            otherImage: $otherImage,
            languages: $valid,
        );
    }
}

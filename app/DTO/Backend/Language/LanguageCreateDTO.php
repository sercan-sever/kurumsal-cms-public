<?php

namespace App\DTO\Backend\Language;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class LanguageCreateDTO implements BaseDTOInterface
{
    /**
     * @param string $name
     * @param string $code
     * @param UploadedFile $image
     * @param StatusEnum|string $status
     * @param StatusEnum|string $default
     * @param int $sorting
     *
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly UploadedFile $image,
        public readonly StatusEnum|string $status,
        public readonly StatusEnum|string $default,
        public readonly int $sorting,
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
            name: $valid['name'],
            code: $valid['code'],
            image: $valid['image'],
            status: $valid['status'],
            default: $valid['default'],
            sorting: $valid['sorting'],
        );
    }
}

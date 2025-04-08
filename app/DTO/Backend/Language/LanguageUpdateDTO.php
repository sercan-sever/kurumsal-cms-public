<?php

namespace App\DTO\Backend\Language;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class LanguageUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     * @param string $name
     * @param string $code
     * @param UploadedFile|null $image
     * @param StatusEnum|string $status
     * @param StatusEnum|string $default
     * @param int $sorting
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $code,
        public readonly ?UploadedFile $image,
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
            id: $valid['id'],
            name: $valid['name'],
            code: $valid['code'],
            image: $valid['image'] ?? null,
            status: $valid['status'],
            default: $valid['default'],
            sorting: $valid['sorting'],
        );
    }
}

<?php

namespace App\DTO\Backend\Brand;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BrandUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $brandId
     * @param UploadedFile|null $image
     * @param int $sorting
     * @param string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $brandId,
        public readonly UploadedFile|null $image,
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

        $brandId = $valid['id'];
        $image    = $valid['image'] ?? null;
        $sorting  = $valid['sorting'];
        $status   = $valid['status'];

        unset($valid['id'], $valid['image'], $valid['sorting'], $valid['status']);

        return new self(
            brandId: $brandId,
            image: $image,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

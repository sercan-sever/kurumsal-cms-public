<?php

namespace App\DTO\Backend\Banner;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BannerCreateDTO implements BaseDTOInterface
{
    /**
     * @param UploadedFile $image
     * @param int $sorting
     * @param StatusEnum|string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly UploadedFile $image,
        public readonly int $sorting,
        public readonly StatusEnum|string $status,
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
        $image   = $valid['image'];
        $sorting = $valid['sorting'];
        $status  = $valid['status'];

        unset($valid['image'], $valid['sorting'], $valid['status']);

        return new self(
            image: $image,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}

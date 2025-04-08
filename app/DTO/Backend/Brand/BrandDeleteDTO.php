<?php

namespace App\DTO\Backend\Brand;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BrandDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $brandId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $brandId,
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
            brandId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

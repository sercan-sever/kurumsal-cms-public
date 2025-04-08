<?php

namespace App\DTO\Backend\Banner;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BannerDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $bannerId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $bannerId,
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
            bannerId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}

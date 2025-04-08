<?php

namespace App\DTO\Backend\Settings\Logo;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class LogoUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $settingId
     * @param UploadedFile $logo
     *
     * @return void
     */
    public function __construct(
        public readonly int $settingId,
        public readonly UploadedFile $logo,
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
            settingId: $valid['setting_id'],
            logo: $valid['image'],
        );
    }
}

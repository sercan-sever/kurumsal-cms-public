<?php

namespace App\DTO\Backend\Settings\Address;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class AddressUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $settingId
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $settingId,
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
        $settingId = $valid['setting_id'];

        unset($valid['setting_id']);

        return new self(
            settingId: $settingId,
            languages: $valid,
        );
    }
}

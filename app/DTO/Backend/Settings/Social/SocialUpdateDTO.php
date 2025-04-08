<?php

namespace App\DTO\Backend\Settings\Social;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class SocialUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $settingId
     * @param string|null $facebook
     * @param string|null $twitter
     * @param string|null $instagram
     * @param string|null $linkedin
     * @param string|null $pinterest
     * @param string|null $youtube
     * @param string|null $whatsapp
     *
     * @return void
     */
    public function __construct(
        public readonly int $settingId,
        public readonly ?string $facebook,
        public readonly ?string $twitter,
        public readonly ?string $instagram,
        public readonly ?string $linkedin,
        public readonly ?string $pinterest,
        public readonly ?string $youtube,
        public readonly ?string $whatsapp,
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
            facebook: $valid['facebook'],
            twitter: $valid['twitter'],
            instagram: $valid['instagram'],
            linkedin: $valid['linkedin'],
            pinterest: $valid['pinterest'],
            youtube: $valid['youtube'],
            whatsapp: $valid['whatsapp'],
        );
    }
}

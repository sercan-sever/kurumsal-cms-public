<?php

namespace App\DTO\Backend\Settings\Plugin;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class PluginUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $settingId
     * @param string|null $siteKey
     * @param string|null $secretKey
     * @param string|null $analytic
     *
     * @return void
     */
    public function __construct(
        public readonly int $settingId,
        public readonly ?string $siteKey,
        public readonly ?string $secretKey,
        public readonly ?string $analytic,
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
            siteKey: $valid['recaptcha_site_key'],
            secretKey: $valid['recaptcha_secret_key'],
            analytic: $valid['google_analytic'],
        );
    }
}

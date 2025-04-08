<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Plugin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePluginRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'setting_id' => [
                'required',
                'integer',
                'min:1',
                'exists:settings,id'
            ],

            'recaptcha_site_key' => [
                'nullable',
                'string',
                'regex:/^[A-Za-z0-9_-]{40,}$/'
            ], // ReCAPTCHA Site Key

            'recaptcha_secret_key' => [
                'nullable',
                'string',
                'regex:/^[A-Za-z0-9_-]{40,}$/'
            ], // ReCAPTCHA Secret Key

            'google_analytic' => [
                'nullable',
                'string',
                'regex:/^G-[A-Za-z0-9]{10}$/'
            ], // Google Analytics 4
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'setting_id'           => 'Ayar ID',
            'recaptcha_site_key'   => 'ReCAPTCHA Site Key',
            'recaptcha_secret_key' => 'ReCAPTCHA Secret Key',
            'google_analytic'      => 'Ölçüm Kimliği',
        ];
    }
}

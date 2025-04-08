<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Social;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialRequest extends FormRequest
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

            'facebook' => [
                'nullable',
                'string',
                'url',
            ],

            'twitter' => [
                'nullable',
                'string',
                'url',
            ],

            'instagram' => [
                'nullable',
                'string',
                'url',
            ],

            'linkedin' => [
                'nullable',
                'string',
                'url',
            ],

            'pinterest' => [
                'nullable',
                'string',
                'url',
            ],

            'youtube' => [
                'nullable',
                'string',
                'url',
            ],

            'whatsapp' => [
                'nullable',
                'string',
                'min:10',
                'max:11',
                'phone:TR',
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'setting_id' => 'Ayar ID',
            'facebook'   => 'Facebook',
            'twitter'    => 'X ( Twitter )',
            'instagram'  => 'Instagram',
            'linkedin'   => 'LinkedIn',
            'pinterest'  => 'Pinterest',
            'youtube'    => 'Youtube',
            'whatsapp'    => 'Whatsapp',
        ];
    }
}

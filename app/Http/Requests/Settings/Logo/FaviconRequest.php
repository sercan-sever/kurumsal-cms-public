<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Logo;

use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class FaviconRequest extends FormRequest
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

            'image' => [
                'required',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_05->value,
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
            'image'      => 'Favicon',
        ];
    }
}

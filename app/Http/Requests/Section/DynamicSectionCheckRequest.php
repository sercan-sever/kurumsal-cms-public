<?php

declare(strict_types=1);

namespace App\Http\Requests\Section;

use App\Enums\Pages\Section\PageSectionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DynamicSectionCheckRequest extends FormRequest
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
            'section' => [
                'required',
                'string',
                Rule::in(PageSectionEnum::getDynamicSection()),
            ]
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'section' => 'Bölüm'
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Translation;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Jobs\JobStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTranslationContentRequest extends FormRequest
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
            'translation_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('translations', 'id')->where('status', JobStatusEnum::COMPLETED->value),
            ],
            'group' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'key' => [
                'required',
                'string',
                'min:1',
                'max:500',
                Rule::unique('translation_contents')
                    ->where('translation_id', $this->translation_id)
                    ->where('group', $this->group),
            ],
            'value' => [
                'required',
                'string',
                'min:1',
                'max:2000'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'translation_id' => 'Çeviri ID',
            'group'          => 'Grup',
            'key'            => 'Anahtar',
            'value'          => 'Değer',
        ];
    }
}

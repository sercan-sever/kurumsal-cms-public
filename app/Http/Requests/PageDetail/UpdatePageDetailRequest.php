<?php

declare(strict_types=1);

namespace App\Http\Requests\PageDetail;

use App\Enums\Defaults\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageDetailRequest extends FormRequest
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
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:pages,id'
            ],

            'sections' => [
                'required',
                'array',
                'min:1',
            ],
            'sections.*' => [
                'distinct:strict',
                Rule::exists('sections', 'id')->where('status', StatusEnum::ACTIVE),
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'         => 'Sayfa ID',
            'sections'   => 'Bölümler',
            'sections.*' => 'Bölüm İçerikleri',
        ];
    }
}

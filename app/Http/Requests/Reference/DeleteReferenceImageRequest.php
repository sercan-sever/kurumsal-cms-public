<?php

declare(strict_types=1);

namespace App\Http\Requests\Reference;

use Illuminate\Foundation\Http\FormRequest;

class DeleteReferenceImageRequest extends FormRequest
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
                'exists:reference_images,id'
            ],

            'reference_id' => [
                'required',
                'integer',
                'min:1',
                'exists:reference_images,reference_id'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'         => 'Referans Görseli ID',
            'service_id' => 'Referans ID',
        ];
    }
}

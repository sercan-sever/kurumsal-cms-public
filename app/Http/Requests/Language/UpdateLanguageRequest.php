<?php

declare(strict_types=1);

namespace App\Http\Requests\Language;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
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
                'exists:languages,id',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],
            'name' => [
                'required',
                'string',
                'min:1',
                'max:200',
                'unique:languages,name,' . $this->id,
            ],
            'code' => [
                'required',
                'string',
                'min:1',
                'max:50',
                'unique:languages,code,' . $this->id,
            ],
            'sorting' => [
                'required',
                'integer',
                'min:1',
            ],
            'status' => [
                'nullable',
                Rule::in(StatusEnum::cases()),
            ],
            'default' => [
                'nullable',
                Rule::in(StatusEnum::cases()),
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'      => 'Language ID',
            'image'   => 'Görsel',
            'name'    => 'Dil Adı',
            'code'    => 'Kısa Gösterim',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
            'default' => 'Varsayılan',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'status'  => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
            'default' => isset($this->default) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Section;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerSectionRequest extends FormRequest
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
                'exists:sections,id'
            ],

            'title' => [
                'required',
                'string',
                'min:1',
                'max:200',
                'unique:sections,title,' . $this->id,
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'sorting' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
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
            'id'      => 'Bölüm ID',
            'image'   => 'Görsel',
            'title'   => 'Bölüm Başlık',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];
    }


    /**
     * Doğrulama İçin Hazırlanacak Alan. Gelen Değeri İstenen Değere Çevirme.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'status'  => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

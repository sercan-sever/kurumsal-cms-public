<?php

declare(strict_types=1);

namespace App\Traits\Validation\Brand;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdateBrandValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:brands,id'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_05->value,
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

        foreach ($languages as $language) {

            $rules["{$language?->code}.title"] = [
                'required',
                'string',
                'min:1',
                'max:200',
                Rule::unique('brand_contents', 'title')
                    ->ignore($this->id, 'brand_id')
                    ->where('language_id', $language->id),
            ];

            $rules["{$language?->code}.description"] = [
                'nullable',
                'string',
                'min:1',
                'max:500'
            ];
        }

        return $rules;
    }

    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateAttributes(Collection $languages): array
    {
        $attributes = [
            'id'      => 'Marka ID',
            'image'   => 'Görsel',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]       = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"] = "Açıklama ( {$language?->getCodeUppercase()} )";
        }

        return $attributes;
    }

    /**
     * @param Collection $languages
     *
     * @return void
     */
    public function generatePrepareForValidation(Collection $languages): void
    {
        $this->merge([
            'status'  => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

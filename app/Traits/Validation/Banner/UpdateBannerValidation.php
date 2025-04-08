<?php

declare(strict_types=1);

namespace App\Traits\Validation\Banner;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdateBannerValidation
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
                'exists:banners,id'
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

        foreach ($languages as $language) {

            $rules["{$language?->code}.title"] = [
                'nullable',
                'string',
                'min:1',
                'max:200'
            ];

            $rules["{$language?->code}.description"] = [
                'nullable',
                'string',
                'min:1',
                'max:500'
            ];

            $rules["{$language?->code}.button_title"] = [
                'nullable',
                'string',
                'min:1',
                'max:200'
            ];

            $rules["{$language?->code}.url"] = [
                'nullable',
                'string',
                'url',
                'min:1',
                'max:600'
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
            'id'      => 'Banner ID',
            'image'   => 'Görsel',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]        = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"]  = "Açıklama ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.button_title"] = "Buton Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.url"]          = "Link ( {$language?->getCodeUppercase()} )";
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

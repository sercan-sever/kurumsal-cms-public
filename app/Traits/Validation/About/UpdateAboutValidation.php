<?php

declare(strict_types=1);

namespace App\Traits\Validation\About;

use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;

trait UpdateAboutValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'other_image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_05->value,
            ],
        ];

        foreach ($languages as $language) {

            $rules["{$language?->code}.title"] = [
                'required',
                'string',
                'min:1',
                'max:200',
            ];

            $rules["{$language?->code}.short_description"] = [
                'required',
                'string',
                'min:5',
                'max:4000',
            ];

            $rules["{$language?->code}.description"] = [
                'required',
                'string',
                'min:1',
            ];

            $rules["{$language?->code}.mission"] = [
                'required',
                'string',
                'min:1',
            ];

            $rules["{$language?->code}.vision"] = [
                'required',
                'string',
                'min:1',
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
            'image'       => 'Görsel',
            'other_image' => 'Biz Kimiz? Görseli',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]             = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.short_description"] = "Biz Kimiz ? ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"]       = "Açıklama ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.mission"]           = "Misyon ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.vision"]            = "Vizyon ( {$language?->getCodeUppercase()} )";
        }

        return $attributes;
    }
}

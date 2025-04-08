<?php

declare(strict_types=1);

namespace App\Traits\Validation\Reference;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait CreateReferenceValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
            'brand_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('brands', 'id')->where('status', StatusEnum::ACTIVE),
            ],

            'image' => [
                'required',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'other_image' => [
                'required',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_05->value,
            ],

            'reference_images' => [
                'nullable',
                'array',
                'max:6',
            ],
            'reference_images.*' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],


            'services' => [
                'required',
                'array',
                'min:1',
            ],
            'services.*' => [
                Rule::exists('services', 'id')->where('status', StatusEnum::ACTIVE),
            ],

            'completion_date' => [
                'required',
                'date',
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
                Rule::unique('reference_contents', 'title')
                    ->where('language_id', $language->id),
            ];

            $rules["{$language?->code}.short_description"] = [
                'required',
                'string',
                'min:5',
                'max:2000',
            ];

            $rules["{$language?->code}.description"] = [
                'required',
                'string',
                'min:1',
            ];

            $rules["{$language?->code}.meta_keywords"] = [
                'nullable',
                'string',
                'min:20',
                'max:150',
            ];

            $rules["{$language?->code}.meta_descriptions"] = [
                'nullable',
                'string',
                'min:50',
                'max:160',
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
            'brand_id'         => 'Marka',
            'image'            => 'Görsel',
            'other_image'      => 'Kapak Görsel',
            'reference_images' => 'Referans Görselleri',
            'services'         => 'Hizmetler',
            'completion_date'  => 'Tamamlanma Tarihi',
            'sorting'          => 'Sıralama',
            'status'           => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]             = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.short_description"] = "Kısa Açıklama ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"]       = "Açıklama ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.meta_keywords"]     = "Meta Anaharat Kelimeler ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.meta_descriptions"] = "Meta Açıklama ( {$language?->getCodeUppercase()} )";
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
            'status' => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

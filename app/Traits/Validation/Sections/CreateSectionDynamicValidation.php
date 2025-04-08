<?php

declare(strict_types=1);

namespace App\Traits\Validation\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait CreateSectionDynamicValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'min:1',
                'max:200',
                'unique:sections,title',
            ],

            'page' => [
                'nullable',
                'int',
                'min:1',
                Rule::exists('pages', 'id')->where('top_page', null)->where('status', StatusEnum::ACTIVE),
            ],

            'image' => [
                'required',
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

            $rules["{$language?->code}.description"] = [
                'required',
                'string',
                'min:1',
            ];

            $rules["{$language?->code}.button_title"] = [
                'nullable',
                'string',
                'min:1',
                'max:200',
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
            'image'        => 'Görsel',
            'title'        => 'Bölüm Başlık',
            'page'         => 'Sayfa',
            'sorting'      => 'Sıralama',
            'status'       => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.description"]  = "İçerik ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.button_title"] = "Buton Başlık ( {$language?->getCodeUppercase()} )";
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

<?php

declare(strict_types=1);

namespace App\Traits\Validation\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdateSectionFooterValidation
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
                'exists:sections,id'
            ],

            'title' => [
                'required',
                'string',
                'min:1',
                'max:200',
                'unique:sections,title,' . $this->id,
            ],

            'page' => [
                'nullable',
                'int',
                'min:1',
                Rule::exists('pages', 'id')->where('top_page', null)->where('status', StatusEnum::ACTIVE),
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

            $rules["{$language?->code}.heading"] = [
                'nullable',
                'string',
                'min:1',
                'max:250',
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
            'id'           => 'Bölüm ID',
            'image'        => 'Görsel',
            'title'        => 'Bölüm Başlık',
            'page'         => 'Sayfa',
            'sorting'      => 'Sıralama',
            'status'       => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.heading"] = "Üst Başlık ( {$language?->getCodeUppercase()} )";
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

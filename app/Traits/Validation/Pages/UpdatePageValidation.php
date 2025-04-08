<?php

declare(strict_types=1);

namespace App\Traits\Validation\Pages;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use App\Enums\Pages\Menu\PageMenuEnum;
use App\Enums\Pages\Page\SubPageDesignEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdatePageValidation
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
                'exists:pages,id'
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'top_page' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('pages', 'id')->where('top_page', null)->where('status', StatusEnum::ACTIVE),
            ],

            'sorting' => [
                'required',
                'integer',
                'min:1',
            ],

            'design' => [
                'required',
                Rule::in(SubPageDesignEnum::cases()),
            ],

            'menu' => [
                'required',
                Rule::in(PageMenuEnum::cases()),
            ],

            'status' => [
                'required',
                Rule::in(StatusEnum::cases()),
            ],

            'breadcrumb' => [
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
                Rule::unique('page_contents', 'title')
                    ->ignore($this->id, 'page_id')
                    ->where('language_id', $language->id),
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
            'id'         => 'Sayfa ID',
            'image'      => 'Sayfa Yolu Görseli',
            'top_page'   => 'Üst Sayfa',
            'sorting'    => 'Sıralama',
            'menu'       => 'Menü Gösterimi',
            'status'     => 'Aktif',
            'breadcrumb' => 'Sayfa Yolu',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]             = "Başlık ( {$language?->getCodeUppercase()} )";
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
            'status'     => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
            'breadcrumb' => isset($this->breadcrumb) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Traits\Validation\Faq;

use App\Enums\Defaults\StatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait CreateFaqValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
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
                Rule::unique('faq_contents', 'title')
                    ->where('language_id', $language->id),
            ];

            $rules["{$language?->code}.description"] = [
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
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]        = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"]  = "Açıklama ( {$language?->getCodeUppercase()} )";
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

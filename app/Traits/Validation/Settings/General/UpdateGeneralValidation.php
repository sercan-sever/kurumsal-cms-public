<?php

declare(strict_types=1);

namespace App\Traits\Validation\Settings\General;

use Illuminate\Support\Collection;

trait UpdateGeneralValidation
{
    /**
     * @param Collection $languages
     *
     * @return array
     */
    public function generateRules(Collection $languages): array
    {
        $rules = [
            'setting_id' => [
                'required',
                'integer',
                'min:1',
                'exists:settings,id',
            ],
        ];

        foreach ($languages as $language) {
            $rules["{$language?->code}.title"] = [
                'required',
                'string',
                'min:10',
                'max:70',
            ];

            $rules["{$language?->code}.meta_keywords"] = [
                'required',
                'string',
                'min:20',
                'max:150',
            ];

            $rules["{$language?->code}.meta_descriptions"] = [
                'required',
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
        $attributes = ['setting_id' => 'Ayar ID'];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"]             = "Site Başlığı ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.meta_keywords"]     = "Meta Anahtar Kelimeler ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.meta_descriptions"] = "Meta Açıklama ( {$language?->getCodeUppercase()} )";
        }

        return $attributes;
    }
}

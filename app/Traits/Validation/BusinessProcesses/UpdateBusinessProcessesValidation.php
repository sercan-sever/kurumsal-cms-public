<?php

declare(strict_types=1);

namespace App\Traits\Validation\BusinessProcesses;

use App\Enums\Defaults\StatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdateBusinessProcessesValidation
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
                'exists:business_processes,id'
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

            $rules["{$language?->code}.header"] = [
                'required',
                'string',
                'min:1',
                'max:200',
                Rule::unique('business_processes_contents', 'header')
                    ->ignore($this->id, 'business_processes_id')
                    ->where('language_id', $language->id),
            ];

            $rules["{$language?->code}.title"] = [
                'required',
                'string',
                'min:1',
                'max:200',
                Rule::unique('business_processes_contents', 'title')
                    ->ignore($this->id, 'business_processes_id')
                    ->where('language_id', $language->id),
            ];

            $rules["{$language?->code}.description"] = [
                'required',
                'string',
                'min:5',
                'max:2000',
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
            'id'      => 'Süreç ID',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.header"]      = "Üst Başlık ( {$language?->getCodeUppercase()} )";
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
            'status' => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

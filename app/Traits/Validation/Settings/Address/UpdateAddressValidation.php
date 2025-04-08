<?php

declare(strict_types=1);

namespace App\Traits\Validation\Settings\Address;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Support\Collection;

trait UpdateAddressValidation
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
            // EMAIL 1
            $rules["{$language?->code}.email_title_one"] = [
                'required',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.email_address_one"] = [
                'required',
                'string',
                'min:6',
                'max:200',
                'email:dns',
                new DisposableEmailCheckRule(),
            ];

            // EMAIL 2
            $rules["{$language?->code}.email_title_two"] = [
                'nullable',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.email_address_two"] = [
                'nullable',
                'string',
                'min:6',
                'max:200',
                "different:{$language?->code}.email_address_one",
                'email:dns',
                new DisposableEmailCheckRule(),
            ];


            // PHONE 1
            $rules["{$language?->code}.phone_title_one"] = [
                'nullable',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.phone_number_one"] = [
                'nullable',
                'string',
                'min:10',
                'max:11',
                'phone:TR',
                // 'phone:' . $language?->getCodeUppercase() ?? 'TR',
            ];

            // PHONE 2
            $rules["{$language?->code}.phone_title_two"] = [
                'nullable',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.phone_number_two"] = [
                'nullable',
                'string',
                'min:10',
                'max:11',
                "different:{$language?->code}.phone_number_one",
                'phone:TR',
                // 'phone:' . $language?->getCodeUppercase() ?? 'TR',
            ];


            // ADDRESS 1
            $rules["{$language?->code}.address_title_one"] = [
                'required',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.address_content_one"] = [
                'required',
                'string',
                'min:1',
                'max:500',
            ];
            $rules["{$language?->code}.address_iframe_one"] = [
                'required',
                'string',
                'regex:/^<iframe[^>]+src="https:\/\/www\.google\.com\/maps\/embed\?pb=[^"]+"[^>]*><\/iframe>$/',
            ];

            // ADDRESS 2
            $rules["{$language?->code}.address_title_two"] = [
                'nullable',
                'string',
                'min:1',
                'max:100',
            ];
            $rules["{$language?->code}.address_content_two"] = [
                'nullable',
                'string',
                'min:1',
                'max:500',
            ];
            $rules["{$language?->code}.address_iframe_two"] = [
                'nullable',
                'string',
                'regex:/^<iframe[^>]+src="https:\/\/www\.google\.com\/maps\/embed\?pb=[^"]+"[^>]*><\/iframe>$/',
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
            $attributes["{$language?->code}.email_title_one"]     = "E-Posta Başlık 1 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.email_address_one"]   = "E-Posta Adres 1 ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.email_title_two"]     = "E-Posta Başlık 2 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.email_address_two"]   = "E-Posta Adres 2 ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.phone_title_one"]     = "Telefon Başlık 1 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.phone_number_one"]    = "Telefon Numarası 1 ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.phone_title_two"]     = "Telefon Başlık 2 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.phone_number_two"]    = "Telefon Numarası 2 ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.address_title_one"]   = "Adres Başlık 1 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.address_content_one"] = "Adres Tarifi 1 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.address_iframe_one"]  = "Adres İframe 1 ( {$language?->getCodeUppercase()} )";

            $attributes["{$language?->code}.address_title_two"]   = "Adres Başlık 2 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.address_content_two"] = "Adres Tarifi 2 ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.address_iframe_two"]  = "Adres İframe 2 ( {$language?->getCodeUppercase()} )";
        }

        return $attributes;
    }
}

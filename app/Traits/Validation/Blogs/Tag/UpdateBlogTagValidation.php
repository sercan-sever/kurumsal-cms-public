<?php

declare(strict_types=1);

namespace App\Traits\Validation\Blogs\Tag;

use App\Enums\Defaults\StatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait UpdateBlogTagValidation
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
                'exists:blog_tags,id'
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
                Rule::unique('blog_tag_contents', 'title')
                    ->ignore($this->id, 'blog_tag_id')
                    ->where('language_id', $language->id),
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
            'id'      => 'Blog Etiket ID',
            'sorting' => 'Sıralama',
            'status'  => 'Aktif',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"] = "Etiket Ad ( {$language?->getCodeUppercase()} )";
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

<?php

declare(strict_types=1);

namespace App\Traits\Validation\Blogs\Blog;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

trait CreateBlogValidation
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
                'required',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'categories' => [
                'required',
                'array',
                'min:1',
                'max:4',
            ],
            'categories.*' => [
                'exists:blog_categories,id',
            ],

            'tags' => [
                'required',
                'array',
                'min:1',
                'max:4',
            ],
            'tags.*' => [
                'exists:blog_tags,id',
            ],

            'published_at' => [
                'required',
                'date',
                'after_or_equal:' . now()->subHour()->format('Y-m-d H:i'),
                'before_or_equal:' . now()->addDays(7)->format('Y-m-d H:i')
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

            'comment_status' => [
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
                Rule::unique('blog_contents', 'title')
                    ->where('language_id', $language->id),
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
            'image'            => 'Görsel',
            'categories'       => 'Kategoriler',
            'tags'             => 'Etiketler',
            'published_at'     => 'Paylaşım Tarihi',
            'sorting'          => 'Sıralama',
            'status'           => 'Aktif',
            'comment_status'   => 'Yorum',
        ];

        foreach ($languages as $language) {
            $attributes["{$language?->code}.title"] = "Başlık ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.description"] = "Açıklama ( {$language?->getCodeUppercase()} )";
            $attributes["{$language?->code}.meta_keywords"] = "Meta Anaharat Kelimeler ( {$language?->getCodeUppercase()} )";
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
            'status'           => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
            'comment_status'   => isset($this->comment_status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

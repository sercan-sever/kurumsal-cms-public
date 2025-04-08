<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Tag;

use Illuminate\Foundation\Http\FormRequest;

class IdBlogTagRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:blog_tags,id'
            ]
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id' => 'Blog Etiket ID',
        ];
    }
}

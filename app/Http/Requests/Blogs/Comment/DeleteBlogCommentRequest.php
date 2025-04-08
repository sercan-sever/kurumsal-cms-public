<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Comment;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBlogCommentRequest extends FormRequest
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
                'exists:blog_comments,id'
            ],

            'deleted_description' => [
                'required',
                'string',
                'min:5',
                'max:200'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'                    => 'Blog Yorum ID',
            'deleted_description'   => 'Silme Nedeni',
        ];
    }
}

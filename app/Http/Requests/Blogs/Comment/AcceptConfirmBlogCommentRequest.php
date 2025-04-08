<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Comment;

use Illuminate\Foundation\Http\FormRequest;

class AcceptConfirmBlogCommentRequest extends FormRequest
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

            'reply_comment' => [
                'nullable',
                'string',
                'min:1',
                'max:2000',
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'            => 'Blog Yorum ID',
            'reply_comment' => 'Yetkili Cevabı',
        ];
    }
}

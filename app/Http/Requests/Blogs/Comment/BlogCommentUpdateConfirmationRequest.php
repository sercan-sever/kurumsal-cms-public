<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Comment;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class BlogCommentUpdateConfirmationRequest extends FormRequest
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

            /* 'name' => [
                'required',
                'string',
                'min:1',
                'max:250',
            ],

            'email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'email:dns',
                new DisposableEmailCheckRule(),
            ], */

            'comment' => [
                'required',
                'string',
                'min:1',
                'max:1000',
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
            'name'          => 'Ad Soyad',
            'email'         => 'E-Posta Adresi',
            'comment'       => 'Kullanıcı Yorumu',
            'reply_comment' => 'Yetkili Cevabı',
        ];
    }
}

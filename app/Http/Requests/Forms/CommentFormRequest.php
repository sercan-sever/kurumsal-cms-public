<?php

declare(strict_types=1);

namespace App\Http\Requests\Forms;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentFormRequest extends FormRequest
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
            'slug' => [
                'required',
                'string',
                'min:1',
                'max:250',
                Rule::exists('blog_contents', 'slug'),
            ],

            'name' => [
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
            ],

            'comment' => [
                'required',
                'string',
                'min:1',
                'max:1000',
            ],

            'g-recaptcha-response' => [
                'required',
                'captcha'
            ],

        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'slug'                 => __('custom.form.blog.slug' ?? ''),
            'name'                 => __('custom.form.name' ?? ''),
            'email'                => __('custom.form.email' ?? ''),
            'comment'              => __('custom.form.comment' ?? ''),
            'g-recaptcha-response' => __('custom.form.recaptcha' ?? ''),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Forms;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscribeFormRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'email:dns',
                new DisposableEmailCheckRule(),
                'unique:blog_subscribes,email',
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
            'email'                => __('custom.form.email' ?? ''),
            'g-recaptcha-response' => __('custom.form.recaptcha' ?? ''),
        ];
    }
}

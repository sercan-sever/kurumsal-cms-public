<?php

declare(strict_types=1);

namespace App\Http\Requests\Login;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class BackendLoginRequest extends FormRequest
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
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'max:200',
            ],

            'g-recaptcha-response' => [
                'required',
                'captcha'
            ],

            'remember' => [
                'required',
                'boolean'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'email'                => 'E-Posta Adresi',
            'password'             => 'Şifre',
            'g-recaptcha-response' => 'Ben Robot Değilim',
            'remember'             => 'Beni Hatırla',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'remember' => isset($this->remember),
        ]);
    }
}

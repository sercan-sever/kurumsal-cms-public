<?php

declare(strict_types=1);

namespace App\Http\Requests\Forms;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class ReferenceFormRequest extends FormRequest
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

            'subject' => [
                'required',
                'string',
                'min:1',
                'max:650',
            ],

            'phone' => [
                'nullable',
                'string',
                // 'min:10',
                // 'max:11',
                'phone:' . request('activeLanguage')?->getCodeUppercase() ?? 'TR',
            ],

            'message' => [
                'required',
                'string',
                'min:1',
                'max:1000',
            ],

            'terms' => [
                'required',
                'accepted'
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
            'name'                 => __('custom.form.name' ?? ''),
            'subject'              => __('custom.form.subject' ?? ''),
            'phone'                => __('custom.form.phone' ?? ''),
            'message'              => __('custom.form.message' ?? ''),
            'terms'                => __('custom.form.terms' ?? ''),
            'g-recaptcha-response' => __('custom.form.recaptcha' ?? ''),
        ];
    }
}

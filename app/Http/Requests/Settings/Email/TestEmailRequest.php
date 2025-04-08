<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Email;

use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class TestEmailRequest extends FormRequest
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
                'different:notification_email',
                'email:dns',
                new DisposableEmailCheckRule(),
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'Test Mail'
        ];
    }
}

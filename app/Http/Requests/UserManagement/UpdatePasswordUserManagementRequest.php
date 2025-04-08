<?php

declare(strict_types=1);

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordUserManagementRequest extends FormRequest
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
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'confirmed',
            ],

            'password_confirmation' => [
                'required',
                'string',
                'min:6',
                'max:200',
            ],

            'send_email' => [
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
            'id'                    => 'ID',
            'password'              => 'Şifre',
            'password_confirmation' => 'Şifre Tekrarı',
            'send_email'            => 'Şifreyi E-Mail İle Gönder',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'send_email' => isset($this->send_email),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\UserManagement;

use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use App\Enums\Permissions\PermissionEnum;
use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserManagementRequest extends FormRequest
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
            'image' => [
                'nullable',
                'image',
                'mimes:' . ImageTypeEnum::MIME->value,
                'max:' . ImageSizeEnum::SIZE_1->value,
            ],

            'name' => [
                'required',
                'string',
                'min:1',
                'max:255'
            ],

            'email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'email:dns',
                new DisposableEmailCheckRule(),
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'min:10',
                'max:11',
                'phone:TR',
                'unique:users,phone'
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

            'admin' => [
                'required',
                'boolean'
            ],

            'permissions' => [
                ($this->admin == false) ? 'required' : 'nullable',
                'array',
                Rule::in(PermissionEnum::cases()),
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'image'                 => 'Avatar',
            'name'                  => 'Kullanıcı Adı',
            'email'                 => 'E-Mail',
            'phone'                 => 'Telefon Numarası',
            'password'              => 'Şifre',
            'password_confirmation' => 'Şifre Tekrarı',
            'send_email'            => 'Şifreyi E-Mail İle Gönder',
            'admin'                 => 'Admin',
            'permissions'           => 'Yetkili İzinleri',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'admin'      => request()->user()->isSuperAdmin() ? isset($this->admin) : false,
            'send_email' => isset($this->send_email),
        ]);
    }
}

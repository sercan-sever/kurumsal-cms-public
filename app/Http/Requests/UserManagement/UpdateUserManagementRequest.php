<?php

declare(strict_types=1);

namespace App\Http\Requests\UserManagement;

use App\Enums\Images\ImageSizeEnum;
use App\Enums\Images\ImageTypeEnum;
use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserManagementRequest extends FormRequest
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
                'unique:users,email,' . $this->id,
            ],

            'phone' => [
                'nullable',
                'string',
                'min:10',
                'max:11',
                'phone:TR',
                'unique:users,phone,' . $this->id,
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'    => 'ID',
            'image' => 'Avatar',
            'name'  => 'Kullanıcı Adı',
            'email' => 'E-Mail',
            'phone' => 'Telefon Numarası',
        ];
    }
}

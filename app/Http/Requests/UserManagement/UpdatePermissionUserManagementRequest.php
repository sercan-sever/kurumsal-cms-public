<?php

declare(strict_types=1);

namespace App\Http\Requests\UserManagement;

use App\Enums\Permissions\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionUserManagementRequest extends FormRequest
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
            'id'          => 'ID',
            'admin'       => 'Admin',
            'permissions' => 'Yetkili İzinleri',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'admin'      => request()->user()->isSuperAdmin() ? isset($this->admin) : false,
        ]);
    }
}

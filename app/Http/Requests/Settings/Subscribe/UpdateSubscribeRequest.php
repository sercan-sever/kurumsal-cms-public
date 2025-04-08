<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Subscribe;

use App\Enums\Defaults\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscribeRequest extends FormRequest
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
           'status' => [
                'required',
                Rule::in(StatusEnum::cases()),
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'status' => 'Abone Aktiflik',
        ];
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'status' => !empty($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

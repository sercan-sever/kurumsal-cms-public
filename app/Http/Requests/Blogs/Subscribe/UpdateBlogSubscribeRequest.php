<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Subscribe;

use App\Enums\Defaults\StatusEnum;
use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogSubscribeRequest extends FormRequest
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
                'exists:blog_subscribes,id'
            ],

            'language' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('languages', 'id')->where('status', StatusEnum::ACTIVE)
            ],

            /* 'email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'unique:blog_subscribes,email,' . $this->id,
                'different:notification_email',
                'email:dns',
                new DisposableEmailCheckRule(),
            ], */

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
            'id'       => 'Blog Abone ID',
            'language' => 'Dil ID',
            /* 'email'    => 'Abone E-Mail', */
            'status'   => 'Abone Durum',
        ];
    }

    /**
     * Doğrulama İçin Hazırlanacak Alan. Gelen Değeri İstenen Değere Çevirme.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'status' => isset($this->status) ? StatusEnum::ACTIVE->value : StatusEnum::PASSIVE->value,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Blogs\Subscribe;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBlogSubscribeRequest extends FormRequest
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

            'deleted_description' => [
                'required',
                'string',
                'min:5',
                'max:200'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'id'                   => 'Blog Abone ID',
            'deleted_description'  => 'Silme Nedeni',
        ];
    }
}

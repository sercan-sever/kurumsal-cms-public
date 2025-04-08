<?php

declare(strict_types=1);

namespace App\Http\Requests\Brand;

use App\Traits\Validation\Brand\UpdateBrandValidation;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    use UpdateBrandValidation;

    /**
     * @var Collection|null
     */
    private ?Collection $requestLanguages;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->requestLanguages = request('languages', collect());
    }

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
        return $this->generateRules(languages: $this->requestLanguages);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return $this->generateAttributes(languages: $this->requestLanguages);
    }


    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->generatePrepareForValidation(languages: $this->requestLanguages);
    }
}

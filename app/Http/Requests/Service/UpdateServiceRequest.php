<?php

declare(strict_types=1);

namespace App\Http\Requests\Service;

use App\Traits\Validation\Service\UpdateServiceValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdateServiceRequest extends FormRequest
{
    use UpdateServiceValidation;


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

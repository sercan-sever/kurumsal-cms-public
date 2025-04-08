<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Address;

use App\Traits\Validation\Settings\Address\UpdateAddressValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdateAddressRequest extends FormRequest
{
    use UpdateAddressValidation;

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
        return $this->generateRules(languages: collect(value: $this->requestLanguages));
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return $this->generateAttributes(languages: collect(value: $this->requestLanguages));
    }
}

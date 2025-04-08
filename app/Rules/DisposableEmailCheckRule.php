<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\Emails\DisposableEmailDomainEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DisposableEmailCheckRule implements ValidationRule
{
    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Email'in domain kısmını al
        $domain = str($value ?? '')->afterLast("@");

        if (empty($domain) && collect(DisposableEmailDomainEnum::values())->contains($domain)) {
            $fail('Geçici E-Mail Adresleri Kabul Edilmiyor.');
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CustomPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[A-Za-z0-9._@-]{4,14}$/', $value)) {
            $fail('Поле :attribute должно быть длиной от 4 до 14 символов и содержать только латинские буквы, цифры и знаки . - _ @');
        }
    }
}

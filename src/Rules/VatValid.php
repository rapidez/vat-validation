<?php

namespace Rapidez\VatValidation\Rules;

use Closure;
use Ibericode\Vat\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class VatValid implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = new Validator;
        $result = Cache::remember('vat_' . $value, config('rapidez.vatvalidation.cache_duration'), function () use ($value, $validator) {
            return $validator->validateVatNumber($value);
        });

        if (!$result) {
            $fail('frontend.vat_validation.failed')->translate();
        }
    }
}

<?php

namespace Rapidez\VatValidation\Rules;

use Closure;
use Ibericode\Vat\Validator;
use Ibericode\Vat\Vies\ViesException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class VatValid implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $validator = new Validator;
            $result = Cache::remember('vat_' . $value, config('rapidez.vatvalidation.cache_duration'), function () use ($value, $validator) {
                return $validator->validateVatNumber($value);
            });

            if (!$result) {
                $fail('Vat validation failed.');
            }
        } catch (ViesException $exception) {
            $fail($exception->getMessage());
        }
    }
}

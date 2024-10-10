<?php

namespace Rapidez\VatValidation\Http\Controllers;

use Ibericode\Vat\Validator;
use Ibericode\Vat\Vies\ViesException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VatController
{
    /** @return array<string, mixed> */
    public function __invoke(Request $request): array
    {
        $request->validate([
            'id' => 'string|required',
        ]);

        if (!config('rapidez.vat-validation.allow-anywhere')) {
            // Check if we should validate here
            abort_unless($request->bearerToken() || $request->headers->get('referer') === route('account.register'), 401);

            if ($request->bearerToken()) {
                config('rapidez.models.quote')::whereQuoteIdOrCustomerToken($request->bearerToken())->firstOrFail();
            }
        }

        try {
            // Try validating the number
            $validator = new Validator;
            $result = Cache::remember('vat_' . $request->id, config('rapidez.vat-validation.cache-duration'), function () use ($request, $validator) {
                return $validator->validateVatNumber($request->id);
            });

            return ['result' => $result];
        } catch (ViesException $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}

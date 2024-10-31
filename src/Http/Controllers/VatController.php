<?php

namespace Rapidez\VatValidation\Http\Controllers;

use Illuminate\Http\Request;
use Rapidez\VatValidation\Rules\VatValid;

class VatController
{
    /** @return array<string, mixed> */
    public function __invoke(Request $request): mixed
    {
        $request->validate([
            'id' => 'string|required',
        ]);

        if (!config('rapidez.vatvalidation.allow_anywhere')) {
            // Check if we should validate here
            abort_unless($request->bearerToken() || $request->headers->get('referer') === route('account.register'), 401);

            if ($request->bearerToken()) {
                config('rapidez.models.quote')::whereQuoteIdOrCustomerToken($request->bearerToken())->firstOrFail();
            }
        }

        $request->validate([
            'id' => new VatValid,
        ]);

        return true;
    }
}

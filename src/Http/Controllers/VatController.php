<?php

namespace Rapidez\VatValidation\Http\Controllers;

use Ibericode\Vat\Validator;
use Ibericode\Vat\Vies\ViesException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VatController
{
    /** @return array<string, mixed> */
    public function __invoke(Request $request): JsonResponse
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

        try {
            // Try validating the number
            $validator = new Validator;
            $result = Cache::remember('vat_' . $request->id, config('rapidez.vatvalidation.cache_duration'), function () use ($request, $validator) {
                return $validator->validateVatNumber($request->id);
            });

            return response()->json($result);
        } catch (ViesException $exception) {
            abort(503, $exception->getMessage());
        }
    }
}

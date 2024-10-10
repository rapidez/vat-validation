<?php

use Rapidez\VatValidation\Http\Controllers\VatController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('vat-validate', VatController::class)
        ->middleware('throttle:'.config('rapidez.vat-validation.rate-limit').',1');
});

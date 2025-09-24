<?php

namespace Rapidez\VatValidation\Http\ViewComposers;

use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class ConfigComposer
{
    public function compose(View $view)
    {
        Config::set('frontend.vat_validation', [
            'force_validation' => config('rapidez.vatvalidation.force_validation'),
            'force_exclusions' => config('rapidez.vatvalidation.force_exclusions'),
            'translations' => __('rapidez-vat::frontend.vat_validation'),
        ]);
    }
}

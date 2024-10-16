<?php

namespace Rapidez\VatValidation\Http\ViewComposers;

use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class ConfigComposer
{
    public function compose(View $view)
    {
        Config::set('frontend.vat_validation.translations', __('rapidez-vat::frontend.vat_validation'));
    }
}

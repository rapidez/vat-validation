<?php

namespace Rapidez\VatValidation;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Rapidez\VatValidation\Http\ViewComposers\ConfigComposer;

class VatValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rapidez/vat-validation.php', 'rapidez.vat-validation');
    }

    public function boot()
    {
        $this
            ->bootComposers()
            ->bootPublishables()
            ->bootRoutes()
            ->bootTranslations();
    }

    protected function bootComposers(): static
    {
        View::composer('rapidez::layouts.app', ConfigComposer::class);

        return $this;
    }

    public function bootPublishables() : self
    {
        $this->publishes([
            __DIR__.'/../config/rapidez/vat-validation.php' => config_path('rapidez/vat-validation.php'),
        ], 'rapidez-vat-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/rapidez-vat'),
        ], 'rapidez-vat-translations');

        return $this;
    }

    public function bootRoutes() : self
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        return $this;
    }

    protected function bootTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'rapidez-vat');

        return $this;
    }
}

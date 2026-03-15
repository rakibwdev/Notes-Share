<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    // This forces Laravel to generate HTTPS links for CSS/JS
    if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme('https');
    }
    }
}

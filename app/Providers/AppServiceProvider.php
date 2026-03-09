<?php

namespace App\Providers;

use App\Models\BrandReferral;
use App\Observers\BrandReferralObserver;
use Illuminate\Support\Facades\View;
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
        BrandReferral::observe(BrandReferralObserver::class);

        View::prependNamespace('filament', resource_path('views/vendor/filament'));
    }
}

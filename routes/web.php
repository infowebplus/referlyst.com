<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ReferralPurchaseSuccessController;
use Illuminate\Support\Facades\Route;

$landingHost = parse_url(config('app.url'), PHP_URL_HOST);
if ($landingHost) {
    Route::domain('{subdomain}.'.$landingHost)
        ->middleware(['web', 'resolve.brand.subdomain'])
        ->group(function (): void {
            Route::get('/', [LandingPageController::class, 'show'])->name('landing.show');
            Route::post('/', [LandingPageController::class, 'submit'])->name('landing.submit');
            Route::get('/terms', [LandingPageController::class, 'terms'])->name('landing.terms');
        });
}

Route::middleware('web')->group(function (): void {
    Route::get('/', [MarketingController::class, 'home'])->name('home');
    Route::get('/features', [MarketingController::class, 'features'])->name('features');
    Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('/compare-plans', [MarketingController::class, 'comparePlans'])->name('compare-plans');
    Route::get('/faq', [MarketingController::class, 'faq'])->name('faq');
    Route::get('/about', [MarketingController::class, 'about'])->name('about');
    Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
    Route::post('/contact', [MarketingController::class, 'submitContact'])->name('contact.submit');
    Route::get('/privacy', [MarketingController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [MarketingController::class, 'terms'])->name('terms');
    Route::get('/login', [MarketingController::class, 'login'])->name('login');
    Route::get('/register', [MarketingController::class, 'register'])->name('register');
    Route::get('/buy-package', [MarketingController::class, 'buyPackage'])->name('buy-package');
    Route::get('/referral-purchase-success', ReferralPurchaseSuccessController::class)
        ->name('referral.purchase.success')
        ->middleware('auth');

    Route::get('/docs/v1', [\App\Http\Controllers\ApiDocsController::class, 'v1'])->name('docs.v1');

    Route::get('{path}/terms', [LandingPageController::class, 'terms'])
        ->name('landing.terms.path')
        ->middleware('resolve.brand.path');

    Route::get('/{path}', [LandingPageController::class, 'show'])
        ->name('landing.show.path')
        ->middleware('resolve.brand.path');
    Route::post('/{path}', [LandingPageController::class, 'submit'])
        ->name('landing.submit.path')
        ->middleware('resolve.brand.path');
});

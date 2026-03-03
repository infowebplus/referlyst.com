<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ReferralPurchaseSuccessController;
use Illuminate\Support\Facades\Route;

$landingHost = parse_url(config('app.url'), PHP_URL_HOST);
if ($landingHost) {
    Route::domain('{subdomain}.'.$landingHost)
        ->middleware(['web', 'resolve.brand.subdomain'])
        ->group(function (): void {
            Route::get('/', [LandingPageController::class, 'show'])->name('landing.show');
            Route::post('/', [LandingPageController::class, 'submit'])->name('landing.submit');
        });
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/referral-purchase-success', ReferralPurchaseSuccessController::class)
    ->name('referral.purchase.success')
    ->middleware(['web', 'auth']);

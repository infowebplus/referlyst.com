<?php

use App\Http\Controllers\Api\ReferralFormApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.brand.token'])->prefix('v1')->group(function (): void {
    Route::get('referral-form', [ReferralFormApiController::class, 'show'])
        ->middleware('api.scope:form')
        ->name('api.referral-form.show');

    Route::post('referrals', [ReferralFormApiController::class, 'submit'])
        ->middleware('api.scope:referrals')
        ->name('api.referrals.submit');
});

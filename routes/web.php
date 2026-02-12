<?php

use App\Http\Controllers\ReferralPurchaseSuccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/referral-purchase-success', ReferralPurchaseSuccessController::class)
    ->name('referral.purchase.success')
    ->middleware(['web', 'auth']);

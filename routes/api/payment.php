<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\PaymentGatewayController;


Route::prefix('v1')->group(function () {
    Route::get('/payments/esewa/success', [PaymentGatewayController::class, 'success'])
        ->name('payments.esewa.success');

    Route::get('/payments/esewa/failure', [PaymentGatewayController::class, 'failure'])
        ->name('payments.esewa.failure');

    Route::get('/payments/khalti/verify/{payment}', [PaymentGatewayController::class, 'verifyKhalti'])
        ->name('payments.khalti.verify');
});

<?php

use App\Http\Controllers\V1\RefundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\PaymentGatewayController;



    Route::get('/payments/esewa/success', [PaymentGatewayController::class, 'success'])
        ->name('payments.esewa.success');

    Route::get('/payments/esewa/failure', [PaymentGatewayController::class, 'failure'])
        ->name('payments.esewa.failure');

    Route::get('/payments/khalti/verify/{payment}', [PaymentGatewayController::class, 'verifyKhalti'])
        ->name('payments.khalti.verify');

    Route::get('refunds', [RefundController::class, 'index']);
    Route::post('payments/{payment}/refunds', [RefundController::class, 'store']);
    Route::get('refunds/{refund}', [RefundController::class, 'show']);
    Route::patch('refunds/{refund}/status', [RefundController::class, 'updateStatus']);

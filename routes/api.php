<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\BookingController;
use App\Http\Controllers\V1\CinemaController;
use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\GenreController;
use App\Http\Controllers\V1\MovieController;
use App\Http\Controllers\V1\ScreenController;
use App\Http\Controllers\V1\SeatCategoryController;
use App\Http\Controllers\V1\SeatController;
use App\Http\Controllers\V1\StaffController;
use App\Http\Controllers\V1\LanguageController;
use App\Http\Controllers\V1\PaymentGatewayController;
use App\Http\Controllers\V1\PeopleController;
use App\Http\Controllers\V1\ShowController;
use App\Http\Controllers\V1\ShowPriceController;
use App\Http\Controllers\V1\ShowScheduleController;
use App\Http\Controllers\V1\ShowSeatController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    // Public auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);



    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/payments/esewa/success', [PaymentGatewayController::class, 'success'])
        ->name('payments.esewa.success');

    Route::get('/payments/esewa/failure', [PaymentGatewayController::class, 'failure'])
        ->name('payments.esewa.failure');



    Route::get(
        'payments/khalti/verify/{payment}',
        [PaymentGatewayController::class, 'verifyKhalti']
    )->name('payments.khalti.verify');

    Route::middleware(['auth:api', 'role:company_admin|BranchManager'])->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);

        Route::post('/logout', [AuthController::class, 'logout']);

        /*
    |--------------------------------------------------------------------------
    | Staff Routes (staff guard)
    |--------------------------------------------------------------------------
    */


        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('cinemas', CinemaController::class);
        Route::apiResource('screens', ScreenController::class);
        Route::apiResource('staff', StaffController::class);
        Route::apiResource('movies', MovieController::class);
        Route::apiResource('genres', GenreController::class);
        Route::apiResource('languages', LanguageController::class);
        Route::apiResource('people', PeopleController::class);
        Route::apiResource('show-schedules', ShowScheduleController::class);
        Route::apiResource('shows', ShowController::class);
        Route::apiResource('show-prices', ShowPriceController::class);
        Route::apiResource('show-seats', ShowSeatController::class);
    });

    Route::middleware(['auth:api', 'role:customer'])
        ->group(function () {

            // Movies
            Route::get('/movies', [MovieController::class, 'index']);
            // Screens
            Route::get('/screens', [ScreenController::class, 'index']);

            // Seat Categories

            // Seats

            // Seat Prices
            //  Route::get('/show-prices', [ShowPriceController::class, 'index']);
            Route::get('show-schedules', [ShowScheduleController::class, 'index']);
            // Shows / Schedules
            //  Route::get('/shows', [ShowController::class, 'index']);

            Route::get('show-seats', [ShowSeatController::class, 'index']);
            Route::post('/bookings', [BookingController::class, 'store']);
            // Booking
            Route::post('payments/verify',         [PaymentGatewayController::class, 'verify']);
        });
});

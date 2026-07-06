<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CinemaController;
use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\ScreenController;
use App\Http\Controllers\V1\StaffController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    // Public auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);



    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware(['auth:api','role:company_admin'])->group(function () {

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
    });
    
});


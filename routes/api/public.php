<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CinemaController;
use App\Http\Controllers\V1\MovieController;
use App\Http\Controllers\V1\ShowScheduleController;
use App\Http\Controllers\V1\ShowSeatController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


 // Browse cinemas
    Route::get('/cinemas', [CinemaController::class, 'index']);
    Route::get('/cinemas/{cinema}', [CinemaController::class, 'show']);

    // Browse movies
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/{movie}', [MovieController::class, 'show']);

    // Show schedules
    Route::get('/show-schedules', [ShowScheduleController::class, 'index']);

    // Seat availability
    Route::get('/show-seats', [ShowSeatController::class, 'index']);
    

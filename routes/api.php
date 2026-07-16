<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\BookingController;
use App\Http\Controllers\V1\CinemaController;
use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\CouponController;
use App\Http\Controllers\V1\GenreController;
use App\Http\Controllers\V1\MovieController;
use App\Http\Controllers\V1\ScreenController;
use App\Http\Controllers\V1\StaffController;
use App\Http\Controllers\V1\LanguageController;
use App\Http\Controllers\V1\LoyaltyController;
use App\Http\Controllers\V1\LoyaltyTransactionController;
use App\Http\Controllers\V1\PaymentGatewayController;
use App\Http\Controllers\V1\PeopleController;
use App\Http\Controllers\V1\RefundController;
use App\Http\Controllers\V1\RolePermissionController;
use App\Http\Controllers\v1\SettingController;
use App\Http\Controllers\V1\ShowScheduleController;
use App\Http\Controllers\V1\ShowSeatController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {




    Route::middleware(['auth:api', 'role:company_admin|branch_manager'])->group(function () {


        Route::post('/logout', [AuthController::class, 'logout']);

        Route::controller(RolePermissionController::class)->prefix('role-permissions')->group(function () {

            Route::post('/assign', 'assign');
            Route::get('/role/{id}', 'show');
            Route::get('/roles', 'indexRoles');
            Route::get('/permissions', 'indexPermissions');
        });

        Route::post(
            'admin/refunds/{refund}/process',
            [RefundController::class, 'process']
        )->middleware('permission:refund.process');

        Route::post('/settings/payment', [SettingController::class, 'updatePaymentSettings']);

        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('cinemas', CinemaController::class);
        Route::apiResource('screens', ScreenController::class);
        Route::apiResource('staff', StaffController::class);
        Route::apiResource('movies', MovieController::class);
        Route::apiResource('genres', GenreController::class);
        Route::apiResource('languages', LanguageController::class);
        Route::apiResource('people', PeopleController::class);
        Route::apiResource('show-schedules', ShowScheduleController::class);
        Route::apiResource('show-seats', ShowSeatController::class);
        Route::apiResource('coupons', CouponController::class);
    });

    Route::middleware(['auth:api', 'role:customer'])
        ->group(function () {

            Route::prefix('loyalty')->group(function () {
                Route::get('/account', [LoyaltyController::class, 'account']);
                Route::get('/history', [LoyaltyController::class, 'history']);
                Route::post('/redeem', [LoyaltyController::class, 'redeem']);
            });

            Route::prefix('loyalty-transactions')->group(function () {

                Route::get('/', [LoyaltyTransactionController::class, 'index']);
                Route::get('/{id}', [LoyaltyTransactionController::class, 'show']);
            });
            Route::post('/bookings', [BookingController::class, 'store']);
            Route::post('payments/verify',         [PaymentGatewayController::class, 'verify']);
        });
});

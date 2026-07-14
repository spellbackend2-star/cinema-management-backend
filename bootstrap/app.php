<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {

            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/public.php'));

            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/payment.php'));
        }
    )


    ->withMiddleware(function (Middleware $middleware): void {
        // bootstrap/app.php (Laravel 11+)
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );


        /*
    |--------------------------------------------------------------------------
    | Model Not Found
    |--------------------------------------------------------------------------
    */

        $exceptions->render(function (
            ModelNotFoundException $e,
            Request $request
        ) {

            return response()->json([

                'success' => false,

                'message' => 'Resource not found or already deleted',

            ], 404);
        });



        /*
    |--------------------------------------------------------------------------
    | Validation Error
    |--------------------------------------------------------------------------
    */

        $exceptions->render(function (
            ValidationException $e,
            Request $request
        ) {

            return response()->json([

                'success' => false,

                'message' => 'Validation error',

                'errors' => $e->errors(),

            ], 422);
        });



        /*
    |--------------------------------------------------------------------------
    | Route Not Found
    |--------------------------------------------------------------------------
    */

        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {

            $previous = $e->getPrevious();


            $message = ($previous instanceof ModelNotFoundException)

                ? class_basename($previous->getModel()) . ' not found'

                : 'Route not found';



            return response()->json([

                'success' => false,

                'message' => $message,

            ], 404);
        });



        /*
    |--------------------------------------------------------------------------
    | Duplicate Database Error
    |--------------------------------------------------------------------------
    */

        $exceptions->render(function (
            QueryException $e,
            Request $request
        ) {


            if ($e->errorInfo[1] == 1062) {


                // Booking seat duplicate
                if (
                    str_contains(
                        $e->getMessage(),
                        'booking_seats_show_seat_id_is_active_unique'
                    )
                ) {


                    return response()->json([

                        'success' => false,

                        'message' => 'Seat already booked.',

                    ], 409);
                }



                return response()->json([

                    'success' => false,

                    'message' => 'Duplicate record already exists.',

                ], 409);
            }
        });




        /*
    |--------------------------------------------------------------------------
    | All Other Exceptions
    |--------------------------------------------------------------------------
    */

        $exceptions->render(function (
            Throwable $e,
            Request $request
        ) {


            return response()->json([

                'success' => false,

                'message' => config('app.debug')

                    ? $e->getMessage()

                    : 'Server Error',

            ], 500);
        });
    })
    ->create();

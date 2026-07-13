<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
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
        // Model Not Found
        $exceptions->render(function (
            ModelNotFoundException $e,
            Request $request
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found or already deleted',
            ], 404);
        });

        // Validation Error
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

        // // Route Not Found
        // $exceptions->render(function (
        //     NotFoundHttpException $e,
        //     Request $request
        // ) {

        //  $model = class_basename($e->getPrevious()->getModel());
        //     return response()->json([
        //         'success' => 'error',
        //         'message' => '{$model} not found',
        //     ], 404);
        // });

        // Route Not Found
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

        // All Other Exceptions
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
    })->create();

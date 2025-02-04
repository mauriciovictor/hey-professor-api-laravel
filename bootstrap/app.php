<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: '',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Handle Validation Exception

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        });

        // Handle Route Not Found Exception
        $exceptions->render(function (Exception $e, Request $request) {

            if (str_contains($e->getMessage(), 'login')) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Unauthorized',
                    ],
                    401
                );
            }

            // Check if the exception message contains anything related to login
            if ($e instanceof RouteNotFoundException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Endpoint not found.',
                    ],
                    404
                );
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Endpoint not found.',
                    ],
                    404
                );
            }
        });
    })->create();

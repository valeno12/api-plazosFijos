<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ID no encontrado -> 404
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El recurso solicitado no fue encontrado.',
                ], 404);
            }
        });

        // Ruta inexistente o modelo no encontrado -> 404
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $message = $e->getPrevious() instanceof ModelNotFoundException
                    ? 'El recurso solicitado no fue encontrado.'
                    : 'La ruta solicitada no existe.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }
        });

        // Método HTTP incorrecto -> 405
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El método HTTP utilizado no está permitido para esta ruta.',
                ], 405);
            }
        });

        // Validación fallida -> 422
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // Error de base de datos -> 503
        $exceptions->renderable(function (QueryException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $message = str_contains($e->getMessage(), 'Connection refused')
                    ? 'No se pudo conectar con la base de datos. Verifique que el servidor esté activo.'
                    : 'Ocurrió un error al procesar la consulta en la base de datos.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 503);
            }
        });

        // Cualquier otro error → 500
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->hasDebugModeEnabled()
                        ? $e->getMessage()
                        : 'Ocurrió un error interno en el servidor.',
                ], 500);
            }
        });

    })->create();

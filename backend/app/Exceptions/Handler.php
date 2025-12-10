<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Manejar error 419 (CSRF token mismatch) de forma amigable
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()
                ->back()
                ->withInput($request->except('password', '_token'))
                ->with('error', 'Tu sesión ha expirado. Por favor, intenta de nuevo.');
        }

        // Si es una petición AJAX/API, devolver JSON
        if ($request->expectsJson()) {
            return $this->renderJsonException($request, $exception);
        }

        // Manejar diferentes tipos de excepciones con vistas personalizadas
        
        // 404 - Modelo no encontrado o ruta no encontrada
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', ['exception' => $exception], 404);
        }

        // 403 - Acceso denegado
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->view('errors.403', ['exception' => $exception], 403);
        }

        // 401 - No autenticado (manejado por Authenticate middleware pero por si acaso)
        if ($exception instanceof AuthenticationException) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }
            return response()->view('errors.unauthenticated', ['exception' => $exception], 401);
        }

        // 422 - Validación (ya se maneja bien por defecto pero podemos personalizarlo)
        if ($exception instanceof ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors' => $exception->errors()
                ], 422);
            }
            // Dejar que Laravel maneje la validación de formularios normalmente
            return parent::render($request, $exception);
        }

        // 500 - Error interno del servidor (cualquier otra excepción)
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();
            
            // Si existe una vista específica para este código de estado, usarla
            if (view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", ['exception' => $exception], $statusCode);
            }
        }

        // Para errores 500 (excepciones no manejadas)
        if (config('app.debug')) {
            // En modo desarrollo, mostrar la página de error de Laravel con toda la info
            return parent::render($request, $exception);
        } else {
            // En producción, mostrar una página de error 500 bonita
            return response()->view('errors.500', ['exception' => $exception], 500);
        }
    }

    /**
     * Renderizar excepciones como JSON para peticiones API/AJAX
     */
    protected function renderJsonException($request, Throwable $exception)
    {
        $statusCode = 500;
        $message = 'Ha ocurrido un error en el servidor.';

        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = 'Recurso no encontrado.';
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $statusCode = 403;
            $message = 'No tienes permiso para acceder a este recurso.';
        }

        if ($exception instanceof AuthenticationException) {
            $statusCode = 401;
            $message = 'No autenticado.';
        }

        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode,
        ];

        // En modo debug, agregar información adicional
        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->map(function ($trace) {
                    return [
                        'file' => $trace['file'] ?? 'N/A',
                        'line' => $trace['line'] ?? 'N/A',
                        'function' => $trace['function'] ?? 'N/A',
                    ];
                })->take(5)->toArray(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Si es petición web, mostrar vista de error bonita
        return response()->view('errors.unauthenticated', ['exception' => $exception], 401);
    }
}

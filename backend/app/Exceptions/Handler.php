<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

        return parent::render($request, $exception);
    }
}

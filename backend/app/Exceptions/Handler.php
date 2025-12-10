<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Handle API requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Throwable $exception)
    {
        // Validation errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $exception->errors()
            ], 422);
        }

        // Model not found
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Authentication errors
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Not found
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Endpoint tidak ditemukan'
            ], 404);
        }

        // Method not allowed
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method tidak diizinkan'
            ], 405);
        }

        // Generic error
        $statusCode = method_exists($exception, 'getStatusCode') 
            ? $exception->getStatusCode() 
            : 500;

        return response()->json([
            'message' => $exception->getMessage() ?: 'Terjadi kesalahan pada server',
            'error' => config('app.debug') ? [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ] : null
        ], $statusCode);
    }
}


<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    public function render($request, Throwable $e)
    {
        // Handle API requests differently
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        // Handle Inertia requests
        if ($request->header('X-Inertia')) {
            return $this->handleInertiaException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException(Request $request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Endpoint not found',
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method not allowed',
            ], 405);
        }

        if ($e instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests',
            ], 429);
        }

        // Log the error for debugging
        \Log::error('API Exception', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => config('app.debug') ? $e->getMessage() : 'Internal server error',
        ], 500);
    }

    /**
     * Handle Inertia exceptions
     */
    protected function handleInertiaException(Request $request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return back()->withErrors($e->errors())->withInput();
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->guest(route('login'));
        }

        if ($e instanceof ModelNotFoundException) {
            return redirect()->back()->with('error', 'The requested resource was not found.');
        }

        if ($e instanceof NotFoundHttpException) {
            return redirect()->back()->with('error', 'The requested page was not found.');
        }

        if ($e instanceof ThrottleRequestsException) {
            return redirect()->back()->with('error', 'Too many requests. Please try again later.');
        }

        // Log the error for debugging
        \Log::error('Inertia Exception', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error',
            config('app.debug') ? $e->getMessage() : 'An unexpected error occurred. Please try again.'
        );
    }
}

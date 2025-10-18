<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18
namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \Throwable $exception The exception that was thrown.
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return ApiResponse::error('Validation failed', 422, $exception->errors());
        }
        return parent::render($request, $exception);
    }

    /**
     * Handle unauthenticated access attempts.
     * This method is triggered when a request fails authentication via middleware such as `auth:sanctum`.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \Illuminate\Auth\AuthenticationException $exception The thrown authentication exception.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return ApiResponse::error('Authentication required. Please log in.', 401);
        }

        return redirect()->guest(route('login'));
    }
}

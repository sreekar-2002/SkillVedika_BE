<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
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
     * Convert an authentication exception into an unauthenticated response.
     *
     * For API requests we return a JSON 401. For non-API requests we try to
     * redirect to the login route if it exists; if not, fall back to JSON 401
     * to avoid RouteNotFoundException when a named 'login' route is not defined.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Treat any request under the api/* path or requests that expect JSON as API
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // For web requests, attempt to redirect to 'login' if available.
        // If route('login') is not defined, return a simple 401 JSON response
        // to avoid throwing a RouteNotFoundException which was causing a 500.
        try {
            return redirect()->guest(route('login'));
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class CleanNullValues
{
    public function handle($request, Closure $next)
    {
        // Clean any undefined/null fields to avoid DB insertion issues
        $request->merge(
            array_filter($request->all(), fn($value) => $value !== null)
        );

        return $next($request);
    }
}


<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exclude API login used by the SPA (Sanctum CSRF handled separately)
        'api/admin/login',
        // Exclude GET requests for leads (pagination/filters) - GET requests don't need CSRF anyway
        'api/leads',
        'api/leads/*',
        'api/admin/logout',
    ];
}

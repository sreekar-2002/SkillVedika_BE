<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;

class StripInvalidFields
{
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $controller = $route?->controller;

        if ($controller && property_exists($controller, 'modelClass')) {
            $model = new $controller->modelClass;

            $columns = Schema::getColumnListing($model->getTable());

            $request->replace(
                collect($request->all())
                ->filter(fn($v, $k) => in_array($k, $columns))
                ->toArray()
            );
        }

        return $next($request);
    }
}


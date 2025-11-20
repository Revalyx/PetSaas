<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tenant_slug === null) {
            return $next($request);
        }

        abort(403, 'Acceso restringido');
    }
}

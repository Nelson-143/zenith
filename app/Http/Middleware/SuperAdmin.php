<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->is_superadmin) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}

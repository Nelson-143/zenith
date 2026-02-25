<?php
namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsDeveloper
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isDeveloper()) { // Adjust this condition as needed
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}

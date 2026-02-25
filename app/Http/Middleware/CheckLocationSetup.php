<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLocationSetup
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->account->is_location_setup) {
            return redirect()->route('location-setup');
        }
        return $next($request);
    }
}
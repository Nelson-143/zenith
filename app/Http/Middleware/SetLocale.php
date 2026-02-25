<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Get the locale from the session (default to 'en' if not set)
        $locale = Session::get('locale', 'en');

        // Set the application locale
        App::setLocale($locale);

        // Continue the request
        return $next($request);
    }
}
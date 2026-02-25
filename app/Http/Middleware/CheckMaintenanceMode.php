<?php
namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the excluded routes dynamically
        $excludedRoutes = [
            'dashboard',
            'admin/clear-cache',
            'admin/block-user/*',
            'admin/ban-ip/*',
            'financial-dashboard',
        ];

        // Check if the current request matches any of the excluded routes
        foreach ($excludedRoutes as $route) {
            if ($request->is($route)) {
                return $next($request); // Allow access
            }
        }

        // Skip maintenance check for admin routes with dynamic prefixes
        $adminRoutePrefix = trim(env('BACKPACK_ROUTE_PREFIX', 'chiefs') . '/' . env('APP_ADMIN_SECRET'), '/');
        if (str_starts_with($request->path(), $adminRoutePrefix)) {
            return $next($request);
        }

        // Proceed with normal maintenance mode check
        if (app()->isDownForMaintenance()) {
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}

<?php
namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    public function handle(Request $request, Closure $next, $feature)
    {
        $user = Auth::user();
        
        if (!$user || !$user->canUseFeature($feature)) {
            return redirect()->route('dashboard')->with('error', 'Your plan does not allow this feature.');
        }
        
        return $next($request);
    }
}
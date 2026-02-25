<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;
class CheckAccountAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next)
{
    $product = $request->route('product') ?? Product::find($request->route('uuid'));
    
    if ($product && is_object($product) && $product->account_id !== auth()->user()->account_id) {
        abort(403, 'Unauthorized access to this product');
    }

    return $next($request);
}
}

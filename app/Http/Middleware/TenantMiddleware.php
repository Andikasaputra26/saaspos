<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $storeId = session('store_id') ?? Auth::user()->store_id ?? null;

            if (!$storeId) {
                return redirect()->route('stores.select')->with('error', 'Pilih toko terlebih dahulu');
            }

            $request->merge(['store_id' => $storeId]);
        }

        return $next($request);
    }
}

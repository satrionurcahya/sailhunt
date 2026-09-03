<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUnit
{
    public function handle(Request $request, Closure $next)
    {
        // Jika TIDAK ada session unit_id, redirect ke login
        if (!session('unit_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
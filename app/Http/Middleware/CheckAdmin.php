<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Unit;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah sudah login
        if (!session('unit_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah unit adalah admin
        $unit = Unit::find(session('unit_id'));
        if (!$unit || !$unit->is_admin) {
            abort(403, 'Akses hanya untuk admin.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek session unit_id
        if (!session('unit_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data unit untuk cek status verifikasi email
        $unit = Unit::find(session('unit_id'));

        return view('dashboard.index', compact('unit'));
    }
}
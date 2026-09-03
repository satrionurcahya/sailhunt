<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua data kompetisi untuk ditampilkan di landing
        $competitions = Competition::orderBy('category')->orderBy('name')->get();

        // Landing tidak butuh data spesifik unit, kirim array kosong agar tidak error di section competition
        $registeredIds = [];
        $existingRegistrations = collect([]);

        return view('landing', compact('competitions', 'registeredIds', 'existingRegistrations'));
    }
}
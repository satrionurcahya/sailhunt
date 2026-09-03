<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class AdminCompetitionController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Competition::withCount('registrations');

        // ============================================================
        // FILTER BERDASARKAN KATEGORI
        // ============================================================
        if ($request->filled('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // ============================================================
        // FILTER BERDASARKAN COMPETITION CATEGORY (Treasure/Bounty)
        // ============================================================
        if ($request->filled('competition_category') && $request->competition_category != 'all') {
            $query->where('competition_category', $request->competition_category);
        }

        // ============================================================
        // SEARCH BERDASARKAN NAMA LOMBA
        // ============================================================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $competitions = $query->orderBy('name')->get();

        return view('admin.competitions.index', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $registrations = Registration::where('competition_id', $competition->id)
            ->with('unit', 'participants')
            ->get();

        return view('admin.competitions.show', compact('competition', 'registrations'));
    }
}
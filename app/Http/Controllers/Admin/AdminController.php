<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Registration;

class AdminController extends Controller
{
    public function index()
    {
        $totalUnits = Unit::count();
        $totalRegistrations = Registration::count();
        $pendingPayments = Registration::where('payment_status', 'paid')->count();
        $totalCompetitions = \App\Models\Competition::count();

        return view('admin.dashboard', compact(
            'totalUnits', 'totalRegistrations', 'pendingPayments', 'totalCompetitions'
        ));
    }
}
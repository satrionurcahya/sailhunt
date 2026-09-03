<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class AdminUnitController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Unit::query();

        // ============================================================
        // SEARCH BERDASARKAN NAMA SEKOLAH ATAU KOTA
        // ============================================================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('city', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // ============================================================
        // FILTER BERDASARKAN STATUS
        // ============================================================
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // ============================================================
        // FILTER BERDASARKAN LEVEL
        // ============================================================
        if ($request->filled('level') && $request->level != 'all') {
            $query->where('level', $request->level);
        }

        $units = $query->orderBy('school_name')->paginate(20);

        // Pertahankan parameter filter di pagination
        $units->appends($request->all());

        return view('admin.units.index', compact('units'));
    }

    public function show(Unit $unit)
    {
        $unit->load('registrations.competition', 'uploads');
        return view('admin.units.show', compact('unit'));
    }

    public function verify(Request $request, Unit $unit)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected,pending',
        ]);

        $oldStatus = $unit->status;
        $unit->update(['status' => $request->status]);

        $this->logAdminActivity('unit_verify', 'admin', 'Verifikasi status unit', [
            'unit_id' => $unit->id,
            'school_name' => $unit->school_name,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
        ]);

        return back()->with('success', 'Status unit berhasil diubah.');
    }
}
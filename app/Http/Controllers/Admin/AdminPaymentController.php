<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class AdminPaymentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Upload::where('type', 'pembayaran')
            ->with('unit', 'registration.competition');

        // ============================================================
        // SEARCH BERDASARKAN NAMA UNIT ATAU LOMBA
        // ============================================================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('unit', function ($sub) use ($search) {
                    $sub->where('school_name', 'LIKE', '%' . $search . '%');
                })->orWhereHas('registration.competition', function ($sub) use ($search) {
                    $sub->where('name', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // ============================================================
        // FILTER BERDASARKAN STATUS
        // ============================================================
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // ============================================================
        // FILTER BERDASARKAN TIPE PEMBAYARAN
        // ============================================================
        if ($request->filled('payment_type') && $request->payment_type != 'all') {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('payment_type', $request->payment_type);
            });
        }

        $uploads = $query->latest()->paginate(20);
        $uploads->appends($request->all());

        return view('admin.payments.index', compact('uploads'));
    }

    public function verify(Request $request, Upload $upload)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $oldStatus = $upload->status;

        $upload->update(['status' => $request->status]);

        // Jika verifikasi pembayaran sukses, update registration payment_status
        if ($request->status == 'verified' && $upload->registration_id) {
            Registration::where('id', $upload->registration_id)
                ->update(['payment_status' => 'verified']);
        }

        // Jika ditolak, kembalikan ke pending agar unit bisa upload ulang
        if ($request->status == 'rejected' && $upload->registration_id) {
            Registration::where('id', $upload->registration_id)
                ->update(['payment_status' => 'pending']);
        }

        // ============================================================
        // LOG AKTIVITAS VERIFIKASI PEMBAYARAN OLEH ADMIN
        // ============================================================
        $this->logAdminActivity('payment_verify', 'admin', 'Verifikasi pembayaran', [
            'upload_id' => $upload->id,
            'unit_id' => $upload->unit_id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'competition' => $upload->registration->competition->name ?? null,
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
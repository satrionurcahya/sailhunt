<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Upload;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNewPaymentNotification;
use App\Traits\LogsActivity;

class PaymentController extends Controller
{
    use LogsActivity;

    public function storeBatch(Request $request)
    {
        $unitId = session('unit_id');

        $registrations = Registration::where('unit_id', $unitId)
            ->where('payment_status', '!=', 'verified')
            ->get();

        if ($registrations->isEmpty()) {
            return back()->with('error', 'Tidak ada lomba yang perlu dibayar.');
        }

        $request->validate([
            'payment_type' => 'required|in:dp,lunas',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $unit = Unit::findOrFail($unitId);
        $fileName = $unit->school_name . '_pembayaran_' . time() . '.' . $request->file('file')->getClientOriginalExtension();

        $fileContent = file_get_contents($request->file('file')->getRealPath());
        Storage::disk('google_pembayaran')->put($fileName, $fileContent, 'public');

        $uploadedFiles = [];

        foreach ($registrations as $reg) {
            $amount = $reg->competition->fee;
            if ($request->payment_type === 'dp') {
                $amount = $amount * 0.6;
            }

            $upload = Upload::create([
                'unit_id'         => $unitId,
                'registration_id' => $reg->id,
                'type'            => 'pembayaran',
                'category'        => $reg->competition->name,
                'file_path'       => $fileName,
                'status'          => 'pending',
            ]);

            $reg->update([
                'payment_status' => 'paid',
                'payment_type'   => $request->payment_type,
                'amount_paid'    => $amount,
            ]);

            $uploadedFiles[] = $upload;
        }

        // ============================================================
        // LOG AKTIVITAS UPLOAD PEMBAYARAN BATCH
        // ============================================================
        $this->logUnitActivity('payment_upload_batch', 'payment', 'Upload bukti pembayaran batch', [
            'total_registrations' => $registrations->count(),
            'payment_type' => $request->payment_type,
            'file_name' => $fileName,
            'total_amount' => $registrations->sum(function($reg) use ($request) {
                return $request->payment_type === 'dp' ? $reg->competition->fee * 0.6 : $reg->competition->fee;
            })
        ]);

        // Kirim notifikasi ke admin
        try {
            $admins = Unit::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                if (!empty($uploadedFiles)) {
                    Mail::to($admin->email)->send(new AdminNewPaymentNotification($uploadedFiles[0]));
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim notifikasi pembayaran batch ke admin: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Semua lomba yang belum lunas akan diverifikasi.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Registration;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class ProfileController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $unit = Unit::findOrFail(session('unit_id'));
        $unit->autoRegisterGPS();

        $registrations = Registration::where('unit_id', session('unit_id'))
            ->with(['competition', 'uploads', 'participants'])
            ->get();

        $daftarUlang = Upload::where('unit_id', session('unit_id'))
            ->where('type', 'daftar_ulang')
            ->latest()
            ->first();

        return view('dashboard.profile.index', compact('unit', 'registrations', 'daftarUlang'));
    }

    public function update(Request $request)
    {
        $unit = Unit::findOrFail(session('unit_id'));

        $data = $request->validate([
            'school_name'    => 'required|string|max:255',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|digits:5',
            'coach_name'     => 'required|string|max:255',
            'trainer_name'   => 'required|string|max:255',
            'commander_name' => 'required|string|max:255',
        ]);

        $oldData = $unit->getOriginal();

        $unit->update($data);

        // ============================================================
        // LOG AKTIVITAS UPDATE PROFIL
        // ============================================================
        $this->logUnitActivity('profile_update', 'profile', 'Update profil unit', [
            'unit_id' => $unit->id,
            'changed_fields' => array_keys($data),
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function uploadDaftarUlang(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $unit = Unit::findOrFail(session('unit_id'));

        $file = $request->file('file');
        $fileName = $unit->school_name . '_daftar-ulang_' . time() . '.' . $file->getClientOriginalExtension();

        $fileContent = file_get_contents($file->getRealPath());
        Storage::disk('google_daftar_ulang')->put($fileName, $fileContent, 'public');

        $upload = Upload::create([
            'unit_id'   => session('unit_id'),
            'type'      => 'daftar_ulang',
            'category'  => 'Daftar Ulang',
            'file_path' => $fileName,
            'status'    => 'pending',
        ]);

        // ============================================================
        // LOG AKTIVITAS UPLOAD DAFTAR ULANG
        // ============================================================
        $this->logUnitActivity('daftar_ulang_upload', 'profile', 'Upload dokumen daftar ulang', [
            'upload_id' => $upload->id,
            'file_name' => $fileName,
        ]);

        return back()->with('success', 'Dokumen daftar ulang berhasil diunggah.');
    }

    public function uploadLomba(Request $request, $registrationId)
    {
        $unitId = session('unit_id');

        $registration = Registration::where('unit_id', $unitId)
            ->where('payment_status', 'verified')
            ->whereHas('competition', function ($q) {
                $q->where('requires_upload', true);
            })
            ->findOrFail($registrationId);

        $competition = $registration->competition;

        if ($competition->upload_deadline && now()->greaterThan($competition->upload_deadline)) {
            return back()->with('error', 'Batas waktu upload karya untuk lomba ini sudah lewat.');
        }

        $unit = Unit::findOrFail($unitId);

        if ($competition->upload_type === 'file') {
            $request->validate([
                'file' => 'required|file|mimes:mp3,wav,ogg|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $unit->school_name . '_lomba_' . time() . '.' . $file->getClientOriginalExtension();

            $fileContent = file_get_contents($file->getRealPath());
            Storage::disk('google_karya')->put($fileName, $fileContent, 'public');

            $path = $fileName;
            $link = null;

        } elseif ($competition->upload_type === 'link') {
            $request->validate([
                'link' => 'required|url',
            ]);

            $path = null;
            $link = $request->input('link');

        } else {
            return back()->with('error', 'Tipe upload tidak dikenali.');
        }

        $upload = Upload::create([
            'unit_id'         => $unitId,
            'registration_id' => $registration->id,
            'type'            => 'lomba',
            'category'        => $competition->name,
            'file_path'       => $path,
            'submission_link' => $link,
            'status'          => 'pending',
        ]);

        // ============================================================
        // LOG AKTIVITAS UPLOAD KARYA LOMBA
        // ============================================================
        $this->logUnitActivity('karya_upload', 'competition', 'Upload karya lomba', [
            'upload_id' => $upload->id,
            'registration_id' => $registration->id,
            'competition' => $competition->name,
            'type' => $competition->upload_type,
        ]);

        return back()->with('success', 'Dokumen lomba berhasil diunggah.');
    }

    public function downloadCard($registrationId)
    {
        $unitId = session('unit_id');

        $registration = Registration::where('unit_id', $unitId)
            ->with(['competition', 'participants', 'unit'])
            ->findOrFail($registrationId);

        $daftarUlang = Upload::where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->first();

        if (!$daftarUlang) {
            return redirect()->route('profile.index')
                ->with('error', 'Anda harus menyelesaikan daftar ulang dan menunggu verifikasi sebelum dapat mengunduh kartu peserta.');
        }

        if (!in_array($registration->payment_status, ['paid', 'verified'])) {
            return redirect()->route('status.index')
                ->with('error', 'Pembayaran belum lunas, kartu peserta belum dapat diunduh.');
        }

        return view('dashboard.card', compact('registration'));
    }
}
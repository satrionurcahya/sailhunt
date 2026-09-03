<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Registration;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class ProfileController extends Controller
{
    use LogsActivity;

    // ============================================================
    // PROFIL UNIT
    // ============================================================

    public function index()
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        $unit = Unit::findOrFail($unitId);

        /*
        |--------------------------------------------------------------------------
        | REGISTRASI GPS OTOMATIS
        |--------------------------------------------------------------------------
        */
        $unit->autoRegisterGPS();

        /*
        |--------------------------------------------------------------------------
        | AMBIL SELURUH REGISTRATION UNIT
        |--------------------------------------------------------------------------
        */
        $registrations = Registration::query()
            ->where('unit_id', $unitId)
            ->with([
                'competition',
                'uploads',
                'participants',
            ])
            ->orderBy('competition_id')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DAFTAR ULANG TERBARU
        |--------------------------------------------------------------------------
        */
        $daftarUlang = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->latest('id')
            ->first();

        return view(
            'dashboard.profile.index',
            compact(
                'unit',
                'registrations',
                'daftarUlang'
            )
        );
    }


    // ============================================================
    // UPDATE PROFIL
    // ============================================================

    public function update(Request $request)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        $unit = Unit::findOrFail($unitId);

        $data = $request->validate([
            'school_name'    => 'required|string|max:255',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|digits:5',
            'coach_name'     => 'required|string|max:255',
            'trainer_name'   => 'required|string|max:255',
            'commander_name' => 'required|string|max:255',
        ]);

        $changedFields = [];

        foreach ($data as $field => $value) {
            if ($unit->{$field} !== $value) {
                $changedFields[] = $field;
            }
        }

        $unit->update($data);

        // ============================================================
        // LOG AKTIVITAS UPDATE PROFIL
        // ============================================================

        $this->logUnitActivity(
            'profile_update',
            'profile',
            'Update profil unit',
            [
                'unit_id'        => $unit->id,
                'changed_fields' => $changedFields,
            ]
        );

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }


    // ============================================================
    // UPLOAD DAFTAR ULANG
    // ============================================================

    public function uploadDaftarUlang(Request $request)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ]);

        $unit = Unit::findOrFail($unitId);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS DAFTAR ULANG TERAKHIR
        |--------------------------------------------------------------------------
        |
        | Aturan:
        |
        | belum ada     -> boleh upload
        | pending       -> tidak boleh upload lagi
        | verified      -> terkunci
        | rejected      -> boleh upload ulang
        |
        */
        $latestDaftarUlang = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->latest('id')
            ->first();

        if ($latestDaftarUlang) {

            if ($latestDaftarUlang->status === 'pending') {
                return back()->with(
                    'error',
                    'Dokumen daftar ulang sedang menunggu verifikasi admin. Silakan menunggu hasil verifikasi.'
                );
            }

            if ($latestDaftarUlang->status === 'verified') {
                return back()->with(
                    'error',
                    'Dokumen daftar ulang Anda sudah diverifikasi dan tidak dapat diganti.'
                );
            }

            /*
             * Status rejected:
             * upload ulang diperbolehkan.
             */
        }

        $file = $request->file('file');

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */
        $safeSchoolName = preg_replace(
            '/[^A-Za-z0-9_-]+/',
            '_',
            $unit->school_name
        );

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $fileName =
            $safeSchoolName
            . '_daftar-ulang_'
            . now()->format('YmdHis')
            . '.'
            . $extension;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD KE GOOGLE DRIVE
        |--------------------------------------------------------------------------
        */
        try {

            $fileContent = file_get_contents(
                $file->getRealPath()
            );

            Storage::disk('google_daftar_ulang')->put(
                $fileName,
                $fileContent,
                'public'
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal upload dokumen daftar ulang.',
                [
                    'unit_id'    => $unitId,
                    'file_name'  => $fileName,
                    'error'      => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Dokumen daftar ulang gagal diunggah. Silakan coba kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN RECORD UPLOAD
        |--------------------------------------------------------------------------
        */
        try {

            $upload = Upload::create([
                'unit_id'   => $unitId,
                'type'      => 'daftar_ulang',
                'category'  => 'Daftar Ulang',
                'file_path' => $fileName,
                'status'    => 'pending',
            ]);

        } catch (\Throwable $e) {

            /*
             * Jika DB gagal, hapus file dari Google Drive
             * agar tidak meninggalkan file yatim.
             */
            try {
                Storage::disk('google_daftar_ulang')
                    ->delete($fileName);
            } catch (\Throwable $deleteException) {

                Log::error(
                    'Gagal menghapus file daftar ulang setelah DB gagal.',
                    [
                        'file_name' => $fileName,
                        'error'     => $deleteException->getMessage(),
                    ]
                );
            }

            Log::error(
                'Gagal menyimpan record daftar ulang.',
                [
                    'unit_id'    => $unitId,
                    'file_name'  => $fileName,
                    'error'      => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data daftar ulang gagal disimpan. Silakan coba kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */
        $this->logUnitActivity(
            'daftar_ulang_upload',
            'profile',
            'Upload dokumen daftar ulang',
            [
                'upload_id' => $upload->id,
                'file_name' => $fileName,
            ]
        );

        return back()->with(
            'success',
            'Dokumen daftar ulang berhasil diunggah dan menunggu verifikasi admin.'
        );
    }


    // ============================================================
    // UPLOAD KARYA LOMBA
    // ============================================================

    public function uploadLomba(
        Request $request,
        $registrationId
    ) {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI REGISTRATION MILIK UNIT
        |--------------------------------------------------------------------------
        */
        $registration = Registration::query()
            ->where('unit_id', $unitId)
            ->where('id', $registrationId)
            ->with('competition')
            ->firstOrFail();

        $competition = $registration->competition;

        if (!$competition) {
            return back()->with(
                'error',
                'Data lomba tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN HARUS SUDAH VERIFIED
        |--------------------------------------------------------------------------
        */
        if ($registration->payment_status !== 'verified') {
            return back()->with(
                'error',
                'Pembayaran lomba ini belum diverifikasi. Karya belum dapat diunggah.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOMBA HARUS MEMBUTUHKAN UPLOAD
        |--------------------------------------------------------------------------
        */
        if (!$competition->requires_upload) {
            return back()->with(
                'error',
                'Lomba ini tidak memerlukan upload karya.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DEADLINE
        |--------------------------------------------------------------------------
        */
        if (
            $competition->upload_deadline
            && now()->greaterThan($competition->upload_deadline)
        ) {
            return back()->with(
                'error',
                'Batas waktu upload karya untuk lomba ini sudah lewat.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK UPLOAD TERAKHIR
        |--------------------------------------------------------------------------
        |
        | Aturan:
        |
        | belum ada    -> boleh upload
        | pending      -> tidak boleh upload baru
        | verified     -> terkunci
        | rejected     -> boleh upload ulang
        |
        */
        $latestUpload = Upload::query()
            ->where('unit_id', $unitId)
            ->where('registration_id', $registration->id)
            ->where('type', 'lomba')
            ->latest('id')
            ->first();

        if ($latestUpload) {

            if ($latestUpload->status === 'pending') {

                return back()->with(
                    'error',
                    'Karya lomba sedang menunggu verifikasi admin. Silakan menunggu hasil verifikasi.'
                );
            }

            if ($latestUpload->status === 'verified') {

                return back()->with(
                    'error',
                    'Karya lomba sudah diverifikasi dan tidak dapat diganti.'
                );
            }

            /*
             * Status rejected:
             * upload ulang diperbolehkan.
             */
        }

        /*
        |--------------------------------------------------------------------------
        | UNIT
        |--------------------------------------------------------------------------
        */
        $unit = Unit::findOrFail($unitId);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD BERDASARKAN TIPE
        |--------------------------------------------------------------------------
        */
        $path = null;
        $link = null;
        $fileName = null;

        if ($competition->upload_type === 'file') {

            $request->validate([
                'file' => [
                    'required',
                    'file',
                    'mimes:mp3,wav,ogg',
                    'max:10240',
                ],
            ]);

            $file = $request->file('file');

            $safeSchoolName = preg_replace(
                '/[^A-Za-z0-9_-]+/',
                '_',
                $unit->school_name
            );

            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            $fileName =
                $safeSchoolName
                . '_lomba_'
                . $registration->id
                . '_'
                . now()->format('YmdHis')
                . '.'
                . $extension;

            /*
            |--------------------------------------------------------------------------
            | UPLOAD GOOGLE DRIVE
            |--------------------------------------------------------------------------
            */
            try {

                $fileContent = file_get_contents(
                    $file->getRealPath()
                );

                Storage::disk('google_karya')->put(
                    $fileName,
                    $fileContent,
                    'public'
                );

                $path = $fileName;

            } catch (\Throwable $e) {

                Log::error(
                    'Gagal upload karya lomba.',
                    [
                        'unit_id'         => $unitId,
                        'registration_id' => $registration->id,
                        'competition'     => $competition->name,
                        'file_name'       => $fileName,
                        'error'           => $e->getMessage(),
                    ]
                );

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Karya lomba gagal diunggah. Silakan coba kembali.'
                    );
            }

        } elseif ($competition->upload_type === 'link') {

            $request->validate([
                'link' => [
                    'required',
                    'url',
                    'max:2048',
                ],
            ]);

            $link = trim(
                $request->input('link')
            );

        } else {

            return back()->with(
                'error',
                'Tipe upload lomba tidak dikenali.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN RECORD UPLOAD
        |--------------------------------------------------------------------------
        */
        try {

            $upload = Upload::create([
                'unit_id'         => $unitId,
                'registration_id' => $registration->id,
                'type'            => 'lomba',
                'category'        => $competition->name,
                'file_path'       => $path,
                'submission_link' => $link,
                'status'          => 'pending',
            ]);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | JIKA DATABASE GAGAL DAN FILE SUDAH DI DRIVE,
            | HAPUS FILE TERSEBUT.
            |--------------------------------------------------------------------------
            */
            if ($fileName) {

                try {

                    Storage::disk('google_karya')
                        ->delete($fileName);

                } catch (\Throwable $deleteException) {

                    Log::error(
                        'Gagal menghapus karya setelah DB gagal.',
                        [
                            'file_name' => $fileName,
                            'error'     => $deleteException->getMessage(),
                        ]
                    );
                }
            }

            Log::error(
                'Gagal menyimpan record karya lomba.',
                [
                    'unit_id'         => $unitId,
                    'registration_id' => $registration->id,
                    'competition'     => $competition->name,
                    'error'           => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data karya gagal disimpan. Silakan coba kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOG AKTIVITAS
        |--------------------------------------------------------------------------
        */
        $this->logUnitActivity(
            'karya_upload',
            'competition',
            'Upload karya lomba',
            [
                'upload_id'       => $upload->id,
                'registration_id' => $registration->id,
                'competition'     => $competition->name,
                'type'            => $competition->upload_type,
            ]
        );

        return back()->with(
            'success',
            'Karya lomba berhasil diunggah dan menunggu verifikasi admin.'
        );
    }


    // ============================================================
    // KARTU PESERTA
    // ============================================================

    public function downloadCard($registrationId)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        $registration = Registration::query()
            ->where('unit_id', $unitId)
            ->with([
                'competition',
                'participants',
                'unit',
            ])
            ->findOrFail($registrationId);

        /*
        |--------------------------------------------------------------------------
        | DAFTAR ULANG HARUS SUDAH VERIFIED
        |--------------------------------------------------------------------------
        */
        $daftarUlang = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->exists();

        if (!$daftarUlang) {
            return redirect()
                ->route('profile.index')
                ->with(
                    'error',
                    'Anda harus menyelesaikan daftar ulang dan menunggu verifikasi sebelum dapat melihat kartu peserta.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN HARUS PAID / VERIFIED
        |--------------------------------------------------------------------------
        */
        if (
            !in_array(
                $registration->payment_status,
                ['paid', 'verified'],
                true
            )
        ) {
            return redirect()
                ->route('status.index')
                ->with(
                    'error',
                    'Pembayaran belum diproses, kartu peserta belum dapat diakses.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | REGISTRATION CODE WAJIB ADA
        |--------------------------------------------------------------------------
        */
        if (!$registration->registration_code) {
            return redirect()
                ->route('status.index')
                ->with(
                    'error',
                    'Kode peserta belum tersedia.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | GUNAKAN KARTU PESERTA RESMI
        |--------------------------------------------------------------------------
        */
        return view(
            'dashboard.kartu-peserta.show',
            compact('registration')
        );
    }
}
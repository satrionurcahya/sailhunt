<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Upload;

class ParticipantCardController extends Controller
{
    /**
     * Menampilkan seluruh kartu peserta
     * milik unit yang sedang login.
     */
    public function index()
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DAFTAR ULANG
        |--------------------------------------------------------------------------
        */
        $daftarUlangVerified = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->exists();

        if (!$daftarUlangVerified) {
            return redirect()
                ->route('profile.index')
                ->with(
                    'error',
                    'Kartu peserta dapat diakses setelah dokumen daftar ulang diverifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL REGISTRATION
        |--------------------------------------------------------------------------
        */
        $registrations = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->whereNotNull('registration_code')
            ->whereIn('payment_status', [
                'paid',
                'verified',
            ])
            ->orderBy('competition_id')
            ->orderBy('id')
            ->get();

        return view(
            'dashboard.kartu-peserta.index',
            compact('registrations')
        );
    }

    /**
     * Menampilkan satu kartu peserta.
     */
    public function show(string $registrationCode)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DAFTAR ULANG
        |--------------------------------------------------------------------------
        */
        $daftarUlangVerified = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->exists();

        if (!$daftarUlangVerified) {
            return redirect()
                ->route('profile.index')
                ->with(
                    'error',
                    'Kartu peserta dapat diakses setelah dokumen daftar ulang diverifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL REGISTRATION
        |--------------------------------------------------------------------------
        |
        | where(unit_id) memastikan unit hanya dapat
        | melihat kartu miliknya sendiri.
        |
        */
        $registration = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->where(
                'registration_code',
                $registrationCode
            )
            ->whereIn('payment_status', [
                'paid',
                'verified',
            ])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI KODE
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

        return view(
            'dashboard.kartu-peserta.show',
            compact('registration')
        );
    }

    /**
     * Menampilkan seluruh kartu dalam satu halaman
     * untuk disimpan sebagai PDF kumulatif.
     */
    public function pdf()
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DAFTAR ULANG
        |--------------------------------------------------------------------------
        */
        $daftarUlangVerified = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->exists();

        if (!$daftarUlangVerified) {
            return redirect()
                ->route('profile.index')
                ->with(
                    'error',
                    'Kartu peserta dapat diakses setelah dokumen daftar ulang diverifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL REGISTRATION
        |--------------------------------------------------------------------------
        */
        $registrations = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->whereNotNull('registration_code')
            ->whereIn('payment_status', [
                'paid',
                'verified',
            ])
            ->orderBy('competition_id')
            ->orderBy('id')
            ->get();

        if ($registrations->isEmpty()) {
            return redirect()
                ->route('participant-cards.index')
                ->with(
                    'error',
                    'Belum ada kartu peserta yang tersedia.'
                );
        }

        return view(
            'dashboard.kartu-peserta.pdf',
            compact('registrations')
        );
    }

    /**
     * Menampilkan satu kartu peserta
     * dalam halaman khusus untuk dibuat menjadi PNG
     * melalui browser.
     */
    public function png(string $registrationCode)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DAFTAR ULANG
        |--------------------------------------------------------------------------
        */
        $daftarUlangVerified = Upload::query()
            ->where('unit_id', $unitId)
            ->where('type', 'daftar_ulang')
            ->where('status', 'verified')
            ->exists();

        if (!$daftarUlangVerified) {
            return redirect()
                ->route('profile.index')
                ->with(
                    'error',
                    'Kartu peserta dapat diakses setelah dokumen daftar ulang diverifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL REGISTRATION
        |--------------------------------------------------------------------------
        */
        $registration = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->where(
                'registration_code',
                $registrationCode
            )
            ->whereIn('payment_status', [
                'paid',
                'verified',
            ])
            ->firstOrFail();

        return view(
            'dashboard.kartu-peserta.png',
            compact('registration')
        );
    }
}
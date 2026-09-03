<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

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
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $registrations = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->whereNotNull('registration_code')
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
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $registration = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        return view(
            'dashboard.kartu-peserta.show',
            compact('registration')
        );
    }


    /**
     * Menampilkan seluruh kartu peserta
     * dalam format halaman PDF kumulatif.
     *
     * PDF dibuat menggunakan fitur Print
     * dari browser agar tidak perlu dependency
     * PDF tambahan di Laravel.
     */
    public function pdf()
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $registrations = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->whereNotNull('registration_code')
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
     * Menampilkan satu kartu dalam halaman khusus
     * untuk proses download PNG melalui browser.
     *
     * PNG dibuat pada sisi browser sehingga
     * tidak membebani server Laravel.
     */
    public function png(string $registrationCode)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $registration = Registration::query()
            ->with([
                'competition',
                'unit',
                'participants',
            ])
            ->where('unit_id', $unitId)
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        return view(
            'dashboard.kartu-peserta.png',
            compact('registration')
        );
    }
}
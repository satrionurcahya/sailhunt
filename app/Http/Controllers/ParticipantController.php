<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Menampilkan daftar peserta
     * berdasarkan kompetisi yang dipilih.
     */
    public function index($competitionId)
    {
        $unitId = session('unit_id');

        $competition = Competition::findOrFail($competitionId);

        $registrations = Registration::where('unit_id', $unitId)
            ->where('competition_id', $competitionId)
            ->with('participants')
            ->get();

        return view(
            'dashboard.participants',
            compact('competition', 'registrations')
        );
    }

    /**
     * Menyimpan / memperbarui peserta
     * untuk suatu registration.
     */
    public function store(Request $request, $competitionId)
    {
        $unitId = session('unit_id');

        $competition = Competition::findOrFail($competitionId);

        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'names' => 'required|array|min:1|max:' . $competition->team_size,
            'names.*' => 'required|string|max:255',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan registration milik unit yang sedang login
        |--------------------------------------------------------------------------
        */
        $registration = Registration::where('unit_id', $unitId)
            ->where('id', $request->registration_id)
            ->where('competition_id', $competitionId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Hapus peserta lama
        |--------------------------------------------------------------------------
        */
        Participant::where(
            'registration_id',
            $registration->id
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Simpan peserta baru
        |--------------------------------------------------------------------------
        */
        foreach ($request->names as $name) {
            Participant::create([
                'unit_id'         => $unitId,
                'competition_id'  => $competitionId,
                'registration_id' => $registration->id,
                'name'            => $name,
            ]);
        }

        return back()->with(
            'success',
            'Peserta berhasil disimpan.'
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index($competitionId)
    {
        $unitId = session('unit_id');
        $competition = Competition::findOrFail($competitionId);

        $registrations = Registration::where('unit_id', $unitId)
                            ->where('competition_id', $competitionId)
                            ->with('participants')
                            ->get();

        return view('dashboard.participants', compact('competition', 'registrations'));
    }

    public function store(Request $request, $competitionId)
    {
        $unitId = session('unit_id');
        $competition = Competition::findOrFail($competitionId);

        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'names' => 'required|array|min:1|max:' . $competition->team_size,
            'names.*' => 'required|string|max:255',
        ]);

        $registration = Registration::where('unit_id', $unitId)
                            ->where('id', $request->registration_id)
                            ->where('competition_id', $competitionId)
                            ->firstOrFail();

        Participant::where('registration_id', $registration->id)->delete();

        foreach ($request->names as $name) {
            Participant::create([
                'unit_id'         => $unitId,
                'competition_id'  => $competitionId,
                'registration_id' => $registration->id,
                'name'            => $name,
            ]);
        }

        return back()->with('success', 'Peserta berhasil disimpan.');
    }
}
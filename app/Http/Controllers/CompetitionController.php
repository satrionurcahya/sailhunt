<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Participant;
use App\Models\Unit;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $unitId = session('unit_id');

        // Ambil SEMUA lomba (termasuk GPS)
        $competitions = Competition::orderBy('category')->orderBy('name')->get();

        $registeredIds = Registration::where('unit_id', $unitId)->pluck('competition_id')->toArray();

        $existingRegistrations = Registration::where('unit_id', $unitId)
            ->with('participants')
            ->get()
            ->groupBy('competition_id');

        return view('dashboard.competitions', compact('competitions', 'registeredIds', 'existingRegistrations'));
    }

    public function storeBatch(Request $request)
    {
        $unitId = session('unit_id');
        $data = $request->input('competitions', []);

        foreach ($data as $competitionId => $info) {
            if (!isset($info['active']) || !$info['active']) continue;

            $competition = Competition::findOrFail($competitionId);

            // CEK DEADLINE PENDAFTARAN
            if ($competition->registration_deadline && now()->greaterThan($competition->registration_deadline)) {
                return back()->with('error', "Pendaftaran untuk lomba {$competition->name} sudah ditutup (deadline: {$competition->registration_deadline}).");
            }

            // Hapus semua registrasi sebelumnya untuk lomba ini (supaya bisa update)
            Registration::where('unit_id', $unitId)->where('competition_id', $competitionId)->delete();

            $teams = $info['teams'] ?? [];
            $maxTeams = $competition->max_teams;
            $teams = array_slice($teams, 0, $maxTeams);

            foreach ($teams as $teamIndex => $participants) {
                $registration = Registration::create([
                    'unit_id'        => $unitId,
                    'competition_id' => $competitionId,
                    'status'         => 'pending',
                ]);

                foreach ($participants as $order => $name) {
                    if (!empty($name)) {
                        Participant::create([
                            'unit_id'         => $unitId,
                            'competition_id'  => $competitionId,
                            'registration_id' => $registration->id,
                            'name'            => $name,
                        ]);
                    }
                }
            }
        }

        // Daftarkan GPS otomatis jika belum
        $unit = Unit::find($unitId);
        if ($unit) {
            $unit->autoRegisterGPS();
        }

        return redirect()->route('competitions.index')->with('success', 'Pendaftaran dan peserta berhasil disimpan!');
    }
}
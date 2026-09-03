<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class AdminScoreController extends Controller
{
    use LogsActivity;

    public function selectCompetition()
    {
        $competitions = Competition::orderBy('name')->get();
        return view('admin.scores.select', compact('competitions'));
    }

    public function input(Competition $competition)
    {
        $registrations = Registration::where('competition_id', $competition->id)
            ->with('unit', 'participants', 'score')
            ->get();

        return view('admin.scores.input', compact('competition', 'registrations'));
    }

    public function store(Request $request, Competition $competition)
    {
        $request->validate([
            'scores' => 'array',
            'scores.*.registration_id' => 'required|exists:registrations,id',
            'scores.*.score' => 'nullable|numeric',
            'scores.*.notes' => 'nullable|string',
        ]);

        foreach ($request->scores as $data) {
            if (isset($data['score']) && $data['score'] !== '') {
                Score::updateOrCreate(
                    ['registration_id' => $data['registration_id']],
                    ['score' => $data['score'], 'notes' => $data['notes'] ?? null]
                );
            }
        }

        $this->calculateRankAndPoints($competition->id);

        // ============================================================
        // LOG AKTIVITAS INPUT SKOR
        // ============================================================
        $this->logAdminActivity('score_input', 'admin', 'Input skor lomba', [
            'competition_id' => $competition->id,
            'competition_name' => $competition->name,
            'total_scored' => count($request->scores ?? []),
        ]);

        return back()->with('success', 'Skor berhasil disimpan.');
    }

    private function calculateRankAndPoints($competitionId)
    {
        $registrations = Registration::where('competition_id', $competitionId)
            ->with('score')
            ->get()
            ->sortByDesc(function ($reg) {
                return $reg->score->score ?? 0;
            });

        $rank = 0;
        $prevScore = null;
        foreach ($registrations as $reg) {
            if ($reg->score) {
                $currentScore = $reg->score->score;
                if ($currentScore !== $prevScore) {
                    $rank++;
                }
                $reg->score->update(['rank' => $rank]);
                $reg->score->points = Score::getPointsByRank($rank);
                $reg->score->save();
                $prevScore = $currentScore;
            }
        }
    }

    public function ranking()
    {
        $rankingData = \App\Models\Unit::getRankingData();

        $juaraUmum = $rankingData->filter(function ($item) {
            return $item->total_points > 0;
        })->values();

        $juaraFavorit = $rankingData->filter(function ($item) {
            return $item->is_favorite;
        })->values();

        return view('admin.scores.ranking', compact('juaraUmum', 'juaraFavorit'));
    }
}
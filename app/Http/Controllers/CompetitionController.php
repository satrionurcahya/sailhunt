<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\Unit;
use App\Services\RegistrationCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompetitionController extends Controller
{
    public function index()
    {
        $unitId = session('unit_id');

        $competitions = Competition::orderBy('category')
            ->orderBy('name')
            ->get();

        $registeredIds = Registration::where('unit_id', $unitId)
            ->pluck('competition_id')
            ->toArray();

        $existingRegistrations = Registration::where('unit_id', $unitId)
            ->with('participants')
            ->orderBy('id')
            ->get()
            ->groupBy('competition_id');

        return view(
            'dashboard.competitions',
            compact(
                'competitions',
                'registeredIds',
                'existingRegistrations'
            )
        );
    }

    public function storeBatch(
        Request $request,
        RegistrationCodeService $registrationCodeService
    ) {
        $unitId = session('unit_id');

        $data = $request->input('competitions', []);

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta sudah berakhir. Silakan login kembali.');
        }

        try {

            DB::transaction(function () use (
                $data,
                $unitId,
                $registrationCodeService
            ) {

                foreach ($data as $competitionId => $info) {

                    if (!isset($info['active']) || !$info['active']) {
                        continue;
                    }

                    /*
                     * Lock competition selama proses pendaftaran.
                     */
                    $competition = Competition::query()
                        ->lockForUpdate()
                        ->findOrFail($competitionId);

                    /*
                     * Cek deadline.
                     */
                    if (
                        $competition->registration_deadline &&
                        now()->greaterThan($competition->registration_deadline)
                    ) {
                        throw ValidationException::withMessages([
                            "competitions.$competitionId" =>
                                "Pendaftaran untuk {$competition->name} sudah ditutup."
                        ]);
                    }

                    $teams = $info['teams'] ?? [];

                    /*
                     * Buang tim yang seluruh inputnya kosong.
                     */
                    $teams = array_values(
                        array_filter(
                            $teams,
                            function ($participants) {
                                return collect($participants)
                                    ->filter(fn ($name) => trim((string) $name) !== '')
                                    ->isNotEmpty();
                            }
                        )
                    );

                    /*
                     * Tidak boleh kosong setelah memilih lomba.
                     */
                    if (count($teams) === 0) {
                        throw ValidationException::withMessages([
                            "competitions.$competitionId" =>
                                "Peserta untuk {$competition->name} belum diisi."
                        ]);
                    }

                    /*
                     * Cek jumlah tim.
                     */
                    if (
                        $competition->max_teams > 0 &&
                        count($teams) > $competition->max_teams
                    ) {
                        throw ValidationException::withMessages([
                            "competitions.$competitionId" =>
                                "Jumlah tim {$competition->name} melebihi batas maksimum."
                        ]);
                    }

                    /*
                     * Ambil registration existing.
                     */
                    $existingRegistrations = Registration::where('unit_id', $unitId)
                        ->where('competition_id', $competition->id)
                        ->with('participants')
                        ->orderBy('id')
                        ->get();

                    /*
                     * Kalau sudah dibayar / diverifikasi,
                     * seluruh registration lomba dikunci.
                     */
                    $locked = $existingRegistrations->contains(
                        fn ($registration) =>
                            in_array(
                                $registration->payment_status,
                                ['paid', 'verified'],
                                true
                            )
                    );

                    if ($locked) {
                        throw ValidationException::withMessages([
                            "competitions.$competitionId" =>
                                "Pendaftaran {$competition->name} sudah dibayar atau diverifikasi dan tidak dapat diubah."
                        ]);
                    }

                    /*
                     * UPDATE / CREATE
                     *
                     * Registration lama dipertahankan supaya
                     * registration_code tetap sama.
                     */
                    foreach ($teams as $teamIndex => $participants) {

                        /*
                         * Pastikan jumlah peserta tepat sesuai team_size.
                         */
                        $participantNames = collect($participants)
                            ->map(fn ($name) => trim((string) $name))
                            ->filter()
                            ->values();

                        if ($participantNames->count() !== (int) $competition->team_size) {
                            throw ValidationException::withMessages([
                                "competitions.$competitionId" =>
                                    "{$competition->name} membutuhkan tepat {$competition->team_size} peserta per tim."
                            ]);
                        }

                        /*
                         * Cegah nama yang sama dalam satu tim.
                         */
                        if ($participantNames->count() !== $participantNames->unique()->count()) {
                            throw ValidationException::withMessages([
                                "competitions.$competitionId" =>
                                    "Nama peserta dalam satu tim {$competition->name} tidak boleh duplikat."
                            ]);
                        }

                        $registration = $existingRegistrations->get($teamIndex);

                        if ($registration) {

                            /*
                             * Registration sudah ada:
                             * update datanya, tetapi kode tetap.
                             */
                            $registration->update([
                                'status' => 'pending',
                            ]);

                            $registration->participants()->delete();

                        } else {

                            /*
                             * Registration baru:
                             * buat kode berurutan.
                             */
                            $registration = $registrationCodeService->create([
                                'unit_id'        => $unitId,
                                'competition_id' => $competition->id,
                                'status'         => 'pending',
                                'payment_status' => 'pending',
                            ]);
                        }

                        foreach ($participantNames as $name) {
                            Participant::create([
                                'unit_id'         => $unitId,
                                'competition_id'  => $competition->id,
                                'registration_id' => $registration->id,
                                'name'            => $name,
                            ]);
                        }
                    }

                    /*
                     * Hapus registration lama yang jumlah timnya
                     * sekarang sudah dikurangi.
                     *
                     * Karena bagian ini hanya dijalankan ketika
                     * belum dibayar dan belum diverifikasi.
                     */
                    if ($existingRegistrations->count() > count($teams)) {

                        $registrationsToDelete = $existingRegistrations
                            ->slice(count($teams));

                        foreach ($registrationsToDelete as $registration) {
                            $registration->participants()->delete();
                            $registration->delete();
                        }
                    }
                }

                /*
                 * GPS otomatis.
                 */
                $unit = Unit::find($unitId);

                if ($unit) {
                    $unit->autoRegisterGPS();
                }
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();

        }

        return redirect()
            ->route('competitions.index')
            ->with(
                'success',
                'Pendaftaran dan peserta berhasil disimpan!'
            );
    }
}
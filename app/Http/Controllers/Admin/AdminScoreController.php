<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminScoreController extends Controller
{
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | SELECT COMPETITION
    |--------------------------------------------------------------------------
    */

    public function selectCompetition()
    {
        $competitions = Competition::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.scores.select',
            compact('competitions')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INPUT SCORE
    |--------------------------------------------------------------------------
    */

    public function input(
        Competition $competition
    ) {
        $registrations = Registration::query()
            ->where(
                'competition_id',
                $competition->id
            )
            ->with([
                'unit',
                'participants',
                'score',
            ])
            ->orderBy('registration_code')
            ->get();

        return view(
            'admin.scores.input',
            compact(
                'competition',
                'registrations'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANUAL SCORE INPUT
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Competition $competition
    ) {
        $validated = $request->validate([
            'scores' => [
                'nullable',
                'array',
            ],

            'scores.*.registration_id' => [
                'required',
                'integer',
                'exists:registrations,id',
            ],

            'scores.*.score' => [
                'nullable',
                'numeric',
            ],

            'scores.*.notes' => [
                'nullable',
                'string',
            ],
        ]);

        $savedCount = 0;

        DB::transaction(
            function () use (
                $validated,
                $competition,
                &$savedCount
            ) {
                foreach (
                    $validated['scores'] ?? []
                    as $data
                ) {
                    $registration =
                        Registration::query()
                            ->where(
                                'id',
                                $data['registration_id']
                            )
                            ->where(
                                'competition_id',
                                $competition->id
                            )
                            ->first();

                    if (!$registration) {
                        throw ValidationException::withMessages([
                            'scores' => [
                                'Ditemukan registration yang tidak sesuai dengan kompetisi yang sedang dinilai.',
                            ],
                        ]);
                    }

                    if (
                        !isset(
                            $data['score']
                        ) ||
                        $data['score'] === ''
                    ) {
                        continue;
                    }

                    Score::updateOrCreate(
                        [
                            'registration_id' =>
                                $registration->id,
                        ],
                        [
                            'score' =>
                                $data['score'],

                            'notes' =>
                                $data['notes'] ?? null,
                        ]
                    );

                    $savedCount++;
                }

                /*
                |--------------------------------------------------------------------------
                | Ranking lomba
                |--------------------------------------------------------------------------
                |
                | Tetap dihitung sebagai hasil evaluasi.
                |
                */

                $this->calculateRankAndPoints(
                    $competition->id
                );
            }
        );

        $this->logAdminActivity(
            'score_input',
            'admin',
            'Input skor lomba secara manual',
            [
                'competition_id' =>
                    $competition->id,

                'competition_name' =>
                    $competition->name,

                'total_saved' =>
                    $savedCount,
            ]
        );

        return back()->with(
            'success',
            $savedCount .
            ' nilai berhasil disimpan dan ranking lomba diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD EXCEL TEMPLATE
    |--------------------------------------------------------------------------
    */

    private function buildScoreTemplate(
        Competition $competition
    ): Spreadsheet {
        $registrations = Registration::query()
            ->where(
                'competition_id',
                $competition->id
            )
            ->with([
                'unit',
                'score',
            ])
            ->orderBy('registration_code')
            ->get();

        $spreadsheet =
            new Spreadsheet();

        $sheet =
            $spreadsheet->getActiveSheet();

        $sheet->setTitle('Nilai');

        $headers = [
            'registration_code',
            'sekolah',
            'level',
            'mata_lomba',
            'score',
            'notes',
        ];

        foreach (
            $headers as $index => $header
        ) {
            $column =
                Coordinate::stringFromColumnIndex(
                    $index + 1
                );

            $sheet->setCellValue(
                $column . '1',
                $header
            );
        }

        $row = 2;

        foreach (
            $registrations as $registration
        ) {
            $sheet->setCellValue(
                'A' . $row,
                $registration
                    ->registration_code ?? ''
            );

            $sheet->setCellValue(
                'B' . $row,
                $registration
                    ->unit
                    ?->school_name ?? ''
            );

            $sheet->setCellValue(
                'C' . $row,
                $registration
                    ->unit
                    ?->level ?? ''
            );

            $sheet->setCellValue(
                'D' . $row,
                $competition->name
            );

            if (
                $registration->score
            ) {
                $sheet->setCellValue(
                    'E' . $row,
                    $registration
                        ->score
                        ->score
                );

                $sheet->setCellValue(
                    'F' . $row,
                    $registration
                        ->score
                        ->notes ?? ''
                );
            }

            $row++;
        }

        $sheet
            ->getStyle('A1:F1')
            ->getFont()
            ->setBold(true);

        $sheet
            ->getStyle('A1:F1')
            ->getAlignment()
            ->setHorizontal('center');

        foreach (
            range('A', 'F') as $column
        ) {
            $sheet
                ->getColumnDimension(
                    $column
                )
                ->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        return $spreadsheet;
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD EXCEL TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function template(
        Competition $competition
    ): StreamedResponse {
        $spreadsheet =
            $this->buildScoreTemplate(
                $competition
            );

        $filename = sprintf(
            'template-nilai-%s.xlsx',
            \Illuminate\Support\Str::slug(
                $competition->name
            )
        );

        $writer =
            new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save(
                    'php://output'
                );
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL SCORE
    |--------------------------------------------------------------------------
    */

    public function import(
        Request $request,
        Competition $competition
    ) {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);

        $file =
            $request->file('file');

        try {
            $spreadsheet =
                IOFactory::load(
                    $file->getRealPath()
                );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal membaca file Excel nilai.',
                [
                    'competition_id' =>
                        $competition->id,

                    'competition_name' =>
                        $competition->name,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'File Excel tidak dapat dibaca. Pastikan file .xlsx atau .xls valid.'
            );
        }

        $sheet =
            $spreadsheet->getActiveSheet();

        $highestRow =
            $sheet->getHighestRow();

        if ($highestRow < 2) {
            return back()->with(
                'error',
                'File Excel tidak memiliki data nilai.'
            );
        }

        $headerRow =
            $sheet->rangeToArray(
                'A1:F1',
                null,
                true,
                true
            )[0];

        $headers = [];

        foreach (
            $headerRow as $index => $value
        ) {
            $headers[$index] =
                strtolower(
                    trim(
                        (string) $value
                    )
                );
        }

        $requiredHeaders = [
            'registration_code',
            'sekolah',
            'level',
            'mata_lomba',
            'score',
            'notes',
        ];

        foreach (
            $requiredHeaders
            as $requiredHeader
        ) {
            if (
                !in_array(
                    $requiredHeader,
                    $headers,
                    true
                )
            ) {
                return back()->with(
                    'error',
                    'Format Excel tidak sesuai. Header "' .
                    $requiredHeader .
                    '" tidak ditemukan.'
                );
            }
        }

        $headerIndexes =
            array_flip($headers);

        $getCellValue =
            function (
                int $columnIndex,
                int $row
            ) use ($sheet) {
                $column =
                    Coordinate::stringFromColumnIndex(
                        $columnIndex + 1
                    );

                return $sheet
                    ->getCell(
                        $column . $row
                    )
                    ->getValue();
            };

        $rows = [];
        $errors = [];

        /*
        |--------------------------------------------------------------------------
        | VALIDATE ROWS
        |--------------------------------------------------------------------------
        */

        for (
            $row = 2;
            $row <= $highestRow;
            $row++
        ) {
            $registrationCode =
                trim(
                    (string) $getCellValue(
                        $headerIndexes[
                            'registration_code'
                        ],
                        $row
                    )
                );

            $schoolName =
                trim(
                    (string) $getCellValue(
                        $headerIndexes[
                            'sekolah'
                        ],
                        $row
                    )
                );

            $level =
                trim(
                    (string) $getCellValue(
                        $headerIndexes[
                            'level'
                        ],
                        $row
                    )
                );

            $competitionName =
                trim(
                    (string) $getCellValue(
                        $headerIndexes[
                            'mata_lomba'
                        ],
                        $row
                    )
                );

            $scoreValue =
                $getCellValue(
                    $headerIndexes['score'],
                    $row
                );

            $notes =
                trim(
                    (string) $getCellValue(
                        $headerIndexes[
                            'notes'
                        ],
                        $row
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                $registrationCode === '' &&
                $schoolName === '' &&
                $level === '' &&
                $competitionName === '' &&
                (
                    $scoreValue === null ||
                    $scoreValue === ''
                ) &&
                $notes === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | REGISTRATION CODE
            |--------------------------------------------------------------------------
            */

            if (
                $registrationCode === ''
            ) {
                $errors[] =
                    "Baris {$row}: registration_code kosong.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | SCORE
            |--------------------------------------------------------------------------
            */

            if (
                $scoreValue === null ||
                $scoreValue === ''
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): score kosong.";

                continue;
            }

            if (
                !is_numeric(
                    $scoreValue
                )
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): score harus berupa angka.";

                continue;
            }

            $scoreValue =
                (float) $scoreValue;

            /*
            |--------------------------------------------------------------------------
            | REGISTRATION
            |--------------------------------------------------------------------------
            */

            $registration =
                Registration::query()
                    ->where(
                        'registration_code',
                        $registrationCode
                    )
                    ->with([
                        'competition',
                        'unit',
                    ])
                    ->first();

            if (!$registration) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): registration tidak ditemukan.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | COMPETITION
            |--------------------------------------------------------------------------
            */

            if (
                (int)
                    $registration
                        ->competition_id
                !==
                (int)
                    $competition->id
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): registration bukan milik kompetisi {$competition->name}.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | SCHOOL
            |--------------------------------------------------------------------------
            */

            $actualSchool =
                trim(
                    (string) (
                        $registration
                            ->unit
                            ?->school_name
                        ?? ''
                    )
                );

            if (
                $schoolName !== '' &&
                strcasecmp(
                    $schoolName,
                    $actualSchool
                ) !== 0
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): nama sekolah tidak sesuai dengan data sistem.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | LEVEL
            |--------------------------------------------------------------------------
            */

            $actualLevel =
                trim(
                    (string) (
                        $registration
                            ->unit
                            ?->level
                        ?? ''
                    )
                );

            if (
                $level !== '' &&
                strcasecmp(
                    $level,
                    $actualLevel
                ) !== 0
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): level tidak sesuai dengan data sistem.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | COMPETITION NAME
            |--------------------------------------------------------------------------
            */

            if (
                $competitionName !== '' &&
                strcasecmp(
                    $competitionName,
                    $competition->name
                ) !== 0
            ) {
                $errors[] =
                    "Baris {$row} ({$registrationCode}): mata lomba tidak sesuai.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALID ROW
            |--------------------------------------------------------------------------
            */

            $rows[] = [
                'registration_id' =>
                    $registration->id,

                'registration_code' =>
                    $registrationCode,

                'score' =>
                    $scoreValue,

                'notes' =>
                    $notes !== ''
                        ? $notes
                        : null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CANCEL WHEN ERROR
        |--------------------------------------------------------------------------
        */

        if (
            !empty($errors)
        ) {
            Log::warning(
                'Import nilai dibatalkan karena terdapat error validasi.',
                [
                    'competition_id' =>
                        $competition->id,

                    'competition_name' =>
                        $competition->name,

                    'errors' =>
                        $errors,
                ]
            );

            return back()->with(
                'import_errors',
                $errors
            );
        }

        if (
            empty($rows)
        ) {
            return back()->with(
                'error',
                'Tidak ada data nilai yang dapat diimport.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DUPLICATE CHECK
        |--------------------------------------------------------------------------
        */

        $duplicateRegistrationIds =
            collect($rows)
                ->groupBy(
                    'registration_id'
                )
                ->filter(
                    fn ($items) =>
                        $items->count() > 1
                )
                ->keys();

        if (
            $duplicateRegistrationIds
                ->isNotEmpty()
        ) {
            $duplicateCodes =
                collect($rows)
                    ->filter(
                        fn ($row) =>
                            in_array(
                                $row[
                                    'registration_id'
                                ],
                                $duplicateRegistrationIds
                                    ->all(),
                                true
                            )
                    )
                    ->pluck(
                        'registration_code'
                    )
                    ->unique()
                    ->implode(', ');

            return back()->with(
                'error',
                'Registration code berikut muncul lebih dari satu kali: ' .
                $duplicateCodes
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $rows,
                $competition
            ) {
                foreach (
                    $rows as $row
                ) {
                    Score::updateOrCreate(
                        [
                            'registration_id' =>
                                $row[
                                    'registration_id'
                                ],
                        ],
                        [
                            'score' =>
                                $row['score'],

                            'notes' =>
                                $row['notes'],
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | RE-CALCULATE RANK & EVALUATION POINTS
                |--------------------------------------------------------------------------
                */

                $this->calculateRankAndPoints(
                    $competition->id
                );
            }
        );

        $this->logAdminActivity(
            'score_import',
            'admin',
            'Import skor lomba dari Excel',
            [
                'competition_id' =>
                    $competition->id,

                'competition_name' =>
                    $competition->name,

                'total_imported' =>
                    count($rows),
            ]
        );

        return back()->with(
            'success',
            count($rows) .
            ' nilai berhasil diimport dan ranking lomba diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE RANK + EVALUATION POINTS
    |--------------------------------------------------------------------------
    |
    | Ranking ini adalah ranking masing-masing mata lomba.
    |
    | Nilai tertinggi = rank terbaik.
    |
    | Points:
    | 1 = 10
    | 2 = 7
    | 3 = 5
    | 4 = 3
    | 5 = 1
    |
    | Points hanya evaluasi.
    |
    */

    private function calculateRankAndPoints(
        int $competitionId
    ): void {
        $registrations =
            Registration::query()
                ->where(
                    'competition_id',
                    $competitionId
                )
                ->with('score')
                ->get()
                ->filter(
                    fn ($registration) =>
                        $registration
                            ->score !== null &&
                        $registration
                            ->score
                            ->score !== null
                )
                ->sortByDesc(
                    fn ($registration) =>
                        (float)
                            $registration
                                ->score
                                ->score
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        foreach (
            $registrations as $registration
        ) {
            $registration
                ->score
                ->update([
                    'rank' => null,
                    'points' => 0,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGN RANK
        |--------------------------------------------------------------------------
        */

        $rank = 0;
        $previousScore = null;

        foreach (
            $registrations as $registration
        ) {
            $currentScore =
                (float)
                    $registration
                        ->score
                        ->score;

            if (
                $previousScore === null ||
                $currentScore !==
                    $previousScore
            ) {
                $rank++;
            }

            $registration
                ->score
                ->update([
                    'rank' =>
                        $rank,

                    'points' =>
                        Score::getPointsByRank(
                            $rank
                        ),
                ]);

            $previousScore =
                $currentScore;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RANKING / REKAP JUARA
    |--------------------------------------------------------------------------
    |
    | Semua unit masuk dalam SATU klasemen.
    |
    | Tidak ada pemisahan Wira/Madya.
    |
    | Juara Umum:
    | jumlah Juara 1 terbanyak.
    |
    | Score/points tetap ditampilkan sebagai evaluasi.
    |
    */

    public function ranking()
    {
        $rankingData =
            \App\Models\Unit::getRankingData();

        /*
        |--------------------------------------------------------------------------
        | JUARA UMUM
        |--------------------------------------------------------------------------
        */

        $juaraUmum =
            $rankingData
                ->filter(
                    function ($item) {
                        return
                            $item->champion_count > 0;
                    }
                )
                ->sortByDesc(
                    'champion_count'
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | JUARA FAVORIT
        |--------------------------------------------------------------------------
        */

        $juaraFavorit =
            $rankingData
                ->filter(
                    function ($item) {
                        return
                            $item->is_favorite;
                    }
                )
                ->sortByDesc(
                    'champion_count'
                )
                ->values();

        return view(
            'admin.scores.ranking',
            compact(
                'juaraUmum',
                'juaraFavorit'
            )
        );
    }
}
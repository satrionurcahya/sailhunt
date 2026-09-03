<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use Carbon\Carbon;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = [
            // =============================================================
            // CABANG PERTOLONGAN PERTAMA
            // =============================================================

            // 1. TREASURE: Pertolongan Pertama Umum
            [
                'name'                  => 'Pertolongan Pertama Umum',
                'category'              => 'Pertolongan Pertama',
                'type'                  => 'pusat',
                'competition_category'  => 'treasure',
                'fee'                   => 80000,
                'team_size'             => 3,
                'max_teams'             => 1,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 2. TREASURE: Pengetahuan Pertolongan Pertama
            [
                'name'                  => 'Pengetahuan Pertolongan Pertama',
                'category'              => 'Pertolongan Pertama',
                'type'                  => 'pusat',
                'competition_category'  => 'treasure',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 3. BOUNTY: Tandu Darurat Ganda Putra
            [
                'name'                  => 'Tandu Darurat Ganda Putra',
                'category'              => 'Pertolongan Pertama',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 40000,
                'team_size'             => 2,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 4. BOUNTY: Tandu Darurat Ganda Putri
            [
                'name'                  => 'Tandu Darurat Ganda Putri',
                'category'              => 'Pertolongan Pertama',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 40000,
                'team_size'             => 2,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 5. BOUNTY: Tandu Darurat Mono
            [
                'name'                  => 'Tandu Darurat Mono',
                'category'              => 'Pertolongan Pertama',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],


            // =============================================================
            // CABANG REMAJA SEHAT PEDULI SESAMA
            // =============================================================

            // 6. TREASURE: Perawatan Keluarga
            [
                'name'                  => 'Perawatan Keluarga',
                'category'              => 'Remaja Sehat Peduli Sesama',
                'type'                  => 'pusat',
                'competition_category'  => 'treasure',
                'fee'                   => 60000,
                'team_size'             => 2,
                'max_teams'             => 1,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 7. TREASURE: Remaja Tanggap Sehat
            [
                'name'                  => 'Remaja Tanggap Sehat',
                'category'              => 'Remaja Sehat Peduli Sesama',
                'type'                  => 'pusat',
                'competition_category'  => 'treasure',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],


            // =============================================================
            // CABANG AYO SIAGA BENCANA
            // =============================================================

            // 8. BOUNTY: BKRK
            [
                'name'                  => 'BKRK',
                'category'              => 'Ayo Siaga Bencana',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 9. BOUNTY: Tas Siaga Bencana
            [
                'name'                  => 'Tas Siaga Bencana',
                'category'              => 'Ayo Siaga Bencana',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 10. BOUNTY: Halang Rintang
            [
                'name'                  => 'Halang Rintang',
                'category'              => 'Ayo Siaga Bencana',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 60000,
                'team_size'             => 5,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],


            // =============================================================
            // CABANG KESEHATAN REMAJA
            // =============================================================

            // 11. BOUNTY: Misi Remaja Sehat Mandiri
            [
                'name'                  => 'Misi Remaja Sehat Mandiri',
                'category'              => 'Kesehatan Remaja',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 35000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 12. BOUNTY: Kampanye Kreatif Remaja Sehat
            [
                'name'                  => 'Kampanye Kreatif Remaja Sehat',
                'category'              => 'Kesehatan Remaja',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 30000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],


            // =============================================================
            // CABANG KEPALANGMERAHAN & KREATIVITAS
            // =============================================================

            // 13. BOUNTY: Donor Darah Sukarela
            [
                'name'                  => 'Donor Darah Sukarela',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 30000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 14. BOUNTY: Sang Jawara (Kepalangmerahan)
            [
                'name'                  => 'Sang Jawara (Kepalangmerahan)',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 30000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 15. BOUNTY: Kepemimpinan
            [
                'name'                  => 'Kepemimpinan',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 30000,
                'team_size'             => 1,
                'max_teams'             => 99,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 16. TREASURE: Cerdas Cermat
            [
                'name'                  => 'Cerdas Cermat',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'pusat',
                'competition_category'  => 'treasure',
                'fee'                   => 35000,
                'team_size'             => 3,
                'max_teams'             => 1,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],

            // 17. BOUNTY: Paduan Suara
            [
                'name'                  => 'Paduan Suara',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 45000,
                'team_size'             => 4,
                'max_teams'             => 99,
                'requires_upload'       => true,
                'upload_type'           => 'file',
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
                'upload_deadline'       => Carbon::parse('2026-09-25 23:59:59'),
            ],

            // 18. BOUNTY: Video Kreatif
            [
                'name'                  => 'Video Kreatif',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 15000,
                'team_size'             => 1,
                'max_teams'             => 1,
                'requires_upload'       => true,
                'upload_type'           => 'link',
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
                'upload_deadline'       => Carbon::parse('2026-09-25 23:59:59'),
            ],

            // 19. BOUNTY: Gerakan Pungut Sampah (GPS) - Gratis
            [
                'name'                  => 'Gerakan Pungut Sampah (GPS)',
                'category'              => 'Kepalangmerahan & Kreativitas',
                'type'                  => 'cabang',
                'competition_category'  => 'bounty',
                'fee'                   => 0,
                'team_size'             => 1,
                'max_teams'             => 1,
                'requires_upload'       => false,
                'upload_type'           => null,
                'registration_deadline' => Carbon::parse('2026-09-22 23:59:59'),
            ],
        ];

        foreach ($competitions as $data) {
            Competition::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
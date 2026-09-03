<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =========================================================
        // 1. TABEL registrations
        // =========================================================
        Schema::table('registrations', function (Blueprint $table) {
            // Indeks untuk payment_status (sering digunakan di filter)
            if (!Schema::hasIndex('registrations', 'registrations_payment_status_index')) {
                $table->index('payment_status');
            }

            // Indeks untuk status (sering digunakan di filter)
            if (!Schema::hasIndex('registrations', 'registrations_status_index')) {
                $table->index('status');
            }

            // Indeks untuk registration_code (sudah unique, otomatis index)
            // Indeks untuk unit_id + competition_id (sudah unique, otomatis index)
        });

        // =========================================================
        // 2. TABEL uploads
        // =========================================================
        Schema::table('uploads', function (Blueprint $table) {
            // Indeks untuk type (sering digunakan di filter daftar_ulang/pembayaran/lomba)
            if (!Schema::hasIndex('uploads', 'uploads_type_index')) {
                $table->index('type');
            }

            // Indeks untuk status (sering digunakan di filter pending/verified/rejected)
            if (!Schema::hasIndex('uploads', 'uploads_status_index')) {
                $table->index('status');
            }

            // Indeks untuk unit_id + type (sering digunakan bersama)
            if (!Schema::hasIndex('uploads', 'uploads_unit_id_type_index')) {
                $table->index(['unit_id', 'type']);
            }
        });

        // =========================================================
        // 3. TABEL units
        // =========================================================
        Schema::table('units', function (Blueprint $table) {
            // Indeks untuk status (sering digunakan di filter admin)
            if (!Schema::hasIndex('units', 'units_status_index')) {
                $table->index('status');
            }

            // Indeks untuk level (sering digunakan di filter)
            if (!Schema::hasIndex('units', 'units_level_index')) {
                $table->index('level');
            }

            // Indeks untuk is_admin (sering digunakan di middleware)
            if (!Schema::hasIndex('units', 'units_is_admin_index')) {
                $table->index('is_admin');
            }
        });

        // =========================================================
        // 4. TABEL competitions
        // =========================================================
        Schema::table('competitions', function (Blueprint $table) {
            // Indeks untuk category (sering digunakan di filter/group by)
            if (!Schema::hasIndex('competitions', 'competitions_category_index')) {
                $table->index('category');
            }

            // Indeks untuk competition_category (treasure/bounty)
            if (!Schema::hasColumn('competitions', 'competition_category')) {
                $table->enum('competition_category', ['treasure', 'bounty'])
                      ->default('bounty')
                      ->after('type')
                      ->comment('treasure = hadiah lebih besar, bounty = hadiah standar');
            }

            if (!Schema::hasIndex('competitions', 'competitions_competition_category_index')) {
                $table->index('competition_category');
            }

            // Indeks untuk registration_deadline (sering digunakan di pengecekan deadline)
            if (!Schema::hasIndex('competitions', 'competitions_registration_deadline_index')) {
                $table->index('registration_deadline');
            }
        });

        // =========================================================
        // 5. TABEL scores
        // =========================================================
        Schema::table('scores', function (Blueprint $table) {
            // Indeks untuk rank (sering digunakan di sorting/ranking)
            if (!Schema::hasIndex('scores', 'scores_rank_index')) {
                $table->index('rank');
            }

            // Indeks untuk points (sering digunakan di ranking)
            if (!Schema::hasColumn('scores', 'points')) {
                $table->integer('points')->default(0)->after('score')->comment('Poin untuk Juara Umum');
            }

            if (!Schema::hasIndex('scores', 'scores_points_index')) {
                $table->index('points');
            }
        });

        // =========================================================
        // 6. TABEL participants
        // =========================================================
        Schema::table('participants', function (Blueprint $table) {
            // Indeks untuk competition_id (sering digunakan di join/where)
            if (!Schema::hasIndex('participants', 'participants_competition_id_index')) {
                $table->index('competition_id');
            }
        });
    }

    public function down(): void
    {
        // Hapus indeks jika rollback
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['status']);
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['unit_id', 'type']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['level']);
            $table->dropIndex(['is_admin']);
        });

        Schema::table('competitions', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['competition_category']);
            $table->dropIndex(['registration_deadline']);
            $table->dropColumn('competition_category');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex(['rank']);
            $table->dropIndex(['points']);
            $table->dropColumn('points');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['competition_id']);
        });
    }
};
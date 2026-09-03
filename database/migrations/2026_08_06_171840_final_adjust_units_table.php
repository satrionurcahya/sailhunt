<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('units', 'address')) {
                $table->string('address', 500)->after('school_name');
            }
            if (!Schema::hasColumn('units', 'commander_name')) {
                $table->string('commander_name')->after('trainer_name');
            }

            // Hapus kolom yang tidak dipakai (jika masih ada)
            if (Schema::hasColumn('units', 'npsn')) {
                $table->dropColumn('npsn');
            }
            if (Schema::hasColumn('units', 'unit_name')) {
                $table->dropColumn('unit_name');
            }
        });
    }

    public function down()
    {
        // Tidak perlu rollback di sini
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('address')->after('school_name');
            $table->string('commander_name')->after('trainer_name');
            // Hapus field yang tidak dipakai jika ada: npsn, unit_name
            $table->dropColumn(['npsn', 'unit_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('competitions', function (Blueprint $table) {
            $table->integer('team_size')->default(1);  // jumlah peserta per tim
            $table->integer('max_teams')->default(1);  // maks tim per unit (1 = hanya 1, 99 = bebas)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            //
        });
    }
};

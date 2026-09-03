<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            // Ubah kolom upload_type menjadi nullable
            $table->enum('upload_type', ['file', 'link'])->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            // Kembalikan ke tidak nullable dengan default 'file'
            $table->enum('upload_type', ['file', 'link'])->nullable(false)->default('file')->change();
        });
    }
};
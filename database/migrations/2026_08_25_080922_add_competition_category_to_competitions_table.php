<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'competition_category')) {
                $table->enum('competition_category', ['treasure', 'bounty'])
                      ->default('bounty')
                      ->after('type')
                      ->comment('treasure = hadiah lebih besar, bounty = hadiah standar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn('competition_category');
        });
    }
};
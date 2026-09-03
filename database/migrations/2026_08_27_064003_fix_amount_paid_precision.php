<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Ubah amount_paid dari decimal(10,2) menjadi decimal(12,2) untuk menampung jumlah yang lebih besar
            if (Schema::hasColumn('registrations', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->change();
            }
        });
    }
};
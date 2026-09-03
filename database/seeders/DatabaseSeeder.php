<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seeder data lomba
        $this->call(CompetitionSeeder::class);

        // Seeder admin
        $this->call(AdminSeeder::class);
    }
}
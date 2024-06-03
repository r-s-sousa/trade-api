<?php

namespace Database\Seeders;

use App\Models\Championship;
use Illuminate\Database\Seeder;

class ChampionshipSeeder extends Seeder
{
    public function run(): void
    {
        Championship::factory()->count(5)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChampionshipTeam;

class ChampionshipTeamSeeder extends Seeder
{
    public function run(): void
    {
        ChampionshipTeam::factory()->count(8)->create();
    }
}

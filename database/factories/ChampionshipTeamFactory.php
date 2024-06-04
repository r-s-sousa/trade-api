<?php

namespace Database\Factories;

use App\Models\Championship;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChampionshipTeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_championship' => Championship::first(),
            'id_team' => Team::factory(),
            'team_points' => $this->faker->numberBetween(0, 20),
            'team_ranking' => $this->faker->numberBetween(1, 8),
            'created_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Championship;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    private static $stages = ['quarter', 'semi', 'third place', 'final'];

    private static $sequencialStages = [
        'quarter', 'quarter', 'quarter', 'quarter', 'semi', 'semi', 'third place', 'final'
    ];

    public function definition(): array
    {
        return [
            'id_team_one' => $this->faker->numberBetween(1, 8),
            'id_team_two' => $this->faker->numberBetween(9, 16),
            'id_championship' => Championship::first(),
            'stage' => array_pop(self::$sequencialStages),
            'team_one_goals' => $this->faker->numberBetween(1, 10),
            'team_two_goals' => $this->faker->numberBetween(1, 10)
        ];
    }
}

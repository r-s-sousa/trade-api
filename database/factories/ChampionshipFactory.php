<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChampionshipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'id_created_by' => User::first(),
            'created_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}

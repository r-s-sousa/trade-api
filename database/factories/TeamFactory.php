<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'created_at' => $this->faker->dateTimeThisMonth()
        ];
    }
}

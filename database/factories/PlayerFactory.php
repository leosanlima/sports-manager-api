<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'position' => $this->faker->randomElement(['G', 'F', 'C']),
            'height' => $this->faker->numberBetween(180, 210),
            'weight' => $this->faker->numberBetween(70, 110),
            'jersey_number' => $this->faker->numberBetween(0, 99),
            'college' => $this->faker->word,
            'country' => $this->faker->country,
            'draft_year' => $this->faker->year,
            'draft_round' => $this->faker->numberBetween(1, 2),
            'draft_number' => $this->faker->numberBetween(1, 60),
            'team_id' => \App\Models\Team::factory(),
        ];
    }
}


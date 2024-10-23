<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'conference' => $this->faker->randomElement(['East', 'West']),
            'division' => $this->faker->randomElement(['Atlantic', 'Central', 'Southeast', 'Northwest', 'Pacific', 'Southwest']),
            'city' => $this->faker->city,
            'name' => $this->faker->word,
            'full_name' => $this->faker->company,
            'abbreviation' => strtoupper($this->faker->lexify('???'))
        ];
    }
}


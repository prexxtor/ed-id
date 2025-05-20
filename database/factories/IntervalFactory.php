<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interval>
 */
class IntervalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->randomNumber(5, false);
        $end = $start + $this->faker->randomNumber(5, false);
        return [
            'start' => $start,
            'end' => $end
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(rand(1, 4), true),
            'body' => $this->faker->text(),
            'image' => $this->faker->image(public_path('images/events'), 700, 500, null, false),
            'date_occurrence' => $this->faker->dateTimeBetween('+1 days', '+2 years')
        ];
    }
}

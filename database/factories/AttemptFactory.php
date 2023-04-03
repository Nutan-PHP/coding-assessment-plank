<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attempt>
 */
class AttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'choice' => fake()->numberBetween(0, 9)
        ];
    }
}

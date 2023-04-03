<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $choices = [
            fake()->words(5, true),
            fake()->words(5, true),
            fake()->words(5, true),
            fake()->words(5, true),
        ];

        return [
            'quiz_id' => Quiz::inRandomOrder()->first()->id,
            'question' => fake()->words(10, true) . "?",
            'choices' => $choices,
            'answer' => fake()->numberBetween(0, 3)
        ];
    }
}

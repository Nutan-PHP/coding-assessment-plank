<?php

namespace Database\Seeders;

use App\Models\Attempt;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();

        Quiz::factory(10)->create();
        Question::factory(100)->create();
        Attempt::factory(200)->create();
    }
}

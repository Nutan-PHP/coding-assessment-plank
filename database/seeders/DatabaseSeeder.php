<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Timeline;
use App\Models\Event;
use App\Models\User;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Timeline::truncate();
        Event::truncate();
        User::truncate();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        User::factory(5)->create();
        Timeline::factory()
                ->count(5)
                ->create()
                ->each(function ($timeline){
                    Event::factory()->count(2)->create([
                        'timeline_id' => $timeline->id
                    ]);
                });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are users to relate to
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        // Create tasks for each existing user
        User::all()->each(function (User $user) {
            Task::factory()
                ->count(rand(5, 15))
                ->state(function () use ($user) {
                    return [
                        'user_id' => $user->id,
                        // Default created_by/updated_by to the owner if null
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                        // Sometimes assign to another random user
                        'assigned_to' => User::inRandomOrder()->value('id'),
                    ];
                })
                ->create();
        });
    }
}

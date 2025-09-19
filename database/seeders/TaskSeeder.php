<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        User::all()->each(function (User $user) {
            Task::factory()
                ->count(5) // fixed number of real tasks per user
                ->state([
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'assigned_to' => User::inRandomOrder()->value('id'),
                ])
                ->create();
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a super admin user (password: "password")
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
                'email_verified_at' => now(),
            ]
        );

        // Create additional real users with deterministic data
        $users = [
            ['name' => 'Ali Khan', 'email' => 'ali.khan@example.com'],
            ['name' => 'Sara Ahmed', 'email' => 'sara.ahmed@example.com'],
            ['name' => 'Usman Malik', 'email' => 'usman.malik@example.com'],
            ['name' => 'Ayesha Noor', 'email' => 'ayesha.noor@example.com'],
            ['name' => 'Bilal Hussain', 'email' => 'bilal.hussain@example.com'],
            ['name' => 'Fatima Zahra', 'email' => 'fatima.zahra@example.com'],
            ['name' => 'Hamza Ali', 'email' => 'hamza.ali@example.com'],
            ['name' => 'Noor Javed', 'email' => 'noor.javed@example.com'],
            ['name' => 'Zainab Raza', 'email' => 'zainab.raza@example.com'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Seed tasks
        $this->call([
            TaskSeeder::class,
        ]);
    }
}

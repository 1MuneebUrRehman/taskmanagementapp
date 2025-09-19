<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;
    protected static int $sequence = 1;

    public function definition(): array
    {
        // Curated list of realistic names
        $names = [
            'Ali Khan', 'Sara Ahmed', 'Usman Malik', 'Ayesha Noor', 'Bilal Hussain',
            'Fatima Zahra', 'Hamza Ali', 'Noor Javed', 'Zainab Raza', 'Imran Qureshi',
            'Sadia Khanum', 'Omar Siddiqui', 'Huda Shaikh', 'Ammar Raza', 'Mahnoor Iqbal',
            'Zeeshan Tariq', 'Rimsha Aslam', 'Faisal Nadeem', 'Nida Arif', 'Hassan Raza'
        ];

        $name = $names[(self::$sequence - 1) % count($names)];
        $base = Str::of($name)->lower()->replace(' ', '.');
        $email = $base . (self::$sequence > count($names) ? self::$sequence : '') . '@example.com';
        self::$sequence++;

        return [
            'name' => (string)$name,
            'email' => (string)$email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'user',
        ];
    }
}

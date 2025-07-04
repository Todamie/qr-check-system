<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(100)->create();

        Role::create([
            'name' => 'admin',
        ]);

        Role::create([
            'name' => 'employee',
        ]);

        Role::create([
            'name' => 'student',
        ]);

        Role::create([
            'name' => 'manual_attendance',
        ]);

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'TYUIU',
            'email' => 'admin@tyuiu.ru',
            'password' => '1234567890'
        ])->roles()->attach([
            Role::where('name', 'admin')->first()->id,
            Role::where('name', 'employee')->first()->id,
            Role::where('name', 'student')->first()->id,
        ]);

        User::create([
            'first_name' => 'Student',
            'last_name' => 'TYUIU',
            'email' => 'student@std.tyuiu.ru',
            'password' => '1234567890'
        ])->roles()->attach(
            Role::where('name', 'student')->first()->id
        );

        User::create([
            'first_name' => 'Employee',
            'last_name' => 'TYUIU',
            'email' => 'employee@tyuiu.ru',
            'password' => '1234567890'
        ])->roles()->attach(
            Role::where('name', 'employee')->first()->id
        );
    }
}

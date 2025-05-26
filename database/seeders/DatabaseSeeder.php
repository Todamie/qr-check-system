<?php

namespace Database\Seeders;

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

        User::create([
            'first_name' => 'Admin',
            'last_name'=> 'TYUIU',
            'email' => 'admin',
            'employee' => true,
            'student' => true,
            'admin' => true,
            'password'=> '1234567890'
        ]);

        User::create([
            'first_name' => 'Student',
            'last_name'=> 'TYUIU',
            'email' => 'student',
            'employee' => false,
            'student' => true,
            'admin' => false,
            'password'=> '1234567890'
        ]);

        User::create([
            'first_name' => 'Employee',
            'last_name'=> 'TYUIU',
            'email' => 'employee',
            'employee' => true,
            'student' => false,
            'admin' => false,
            'password'=> '1234567890'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample users with profile data
        User::create([
            'name' => 'Rafi Cahya',
            'first_name' => 'Rafi',
            'last_name' => 'Cahya',
            'email' => 'raficahya08@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'date_of_birth' => '2004-08-07',
            'gender' => 'Laki-laki',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'first_name' => 'Siti',
            'last_name' => 'Aminah',
            'email' => 'siti.aminah@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'date_of_birth' => '1995-03-15',
            'gender' => 'Perempuan',
            'phone' => '081298765432',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admin MNL',
            'first_name' => 'Admin',
            'last_name' => 'MNL',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'date_of_birth' => '1980-01-01',
            'gender' => 'Laki-laki',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);
    }
}

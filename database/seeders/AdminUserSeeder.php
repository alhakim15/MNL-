<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user', 'guard_name' => 'web']);
        }

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mnl.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'MNL',
                'name' => 'Admin MNL',
                'email' => 'admin@mnl.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        // Create a test user (unverified)
        $testUser = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => null, // Unverified
            ]
        );

        // Assign user role
        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }

        $this->command->info('Admin user created: admin@mnl.com / password123');
        $this->command->info('Test user created: user@test.com / password123 (unverified)');
    }
}

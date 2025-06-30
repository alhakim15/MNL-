<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeders in the correct order
        $this->call([
            UserSeeder::class,          // Creates roles, permissions, and test users
            AdminUserSeeder::class,     // Creates admin user (if needed separately)
            UserProfileSeeder::class,   // Profile-related seeding (if exists)
            ShipRouteSeeder::class,     // Creates ship routes after ships and cities exist
        ]);

        $this->command->info('All seeders completed successfully!');
    }
}

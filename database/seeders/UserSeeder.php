<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Define roles with their respective permissions.
     */
    private const ROLES_AND_PERMISSIONS = [
        'admin' => [
            'view_dashboard',
            'manage_users',
            'manage_deliveries',
            'manage_ships',
            'manage_cities',
            'manage_infographics',
            'view_all_deliveries',
            'create_delivery',
            'edit_delivery',
            'delete_delivery',
            'approve_delivery',
            'reject_delivery',
        ],
        'user' => [
            'view_dashboard',
            'view_own_deliveries',
            'create_delivery',
            'edit_own_delivery',
            'view_ships',
            'view_cities',
            'view_infographics',
        ],
    ];

    /**
     * Define test users to create.
     */
    private const TEST_USERS = [
        [
            'email' => 'admin@admin.com',
            'name' => 'Admin User',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => true,
        ],
        [
            'email' => 'user@example.com',
            'name' => 'Regular User',
            'first_name' => 'Regular',
            'last_name' => 'User',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => true,
        ],
        [
            'email' => 'unverified@example.com',
            'name' => 'Unverified User',
            'first_name' => 'Unverified',
            'last_name' => 'User',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => false,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->createPermissions();
            $this->createRolesWithPermissions();
            $this->createTestUsers();
        });
    }

    /**
     * Create all permissions.
     */
    private function createPermissions(): void
    {
        $allPermissions = collect(self::ROLES_AND_PERMISSIONS)
            ->flatten()
            ->unique()
            ->values();

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Clear cache to ensure permissions are available
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info(sprintf('Created %d permissions.', $allPermissions->count()));
    }

    /**
     * Create roles and assign permissions to them.
     */
    private function createRolesWithPermissions(): void
    {
        foreach (self::ROLES_AND_PERMISSIONS as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            // Get permission objects
            $permissionObjects = Permission::whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->get();

            // Sync permissions to the role
            $role->syncPermissions($permissionObjects);

            $this->command->info(sprintf(
                'Role "%s" created with %d permissions.',
                $roleName,
                $permissionObjects->count()
            ));
        }
    }

    /**
     * Create test users with proper roles.
     */
    private function createTestUsers(): void
    {
        foreach (self::TEST_USERS as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'], // Will be hashed by model casting
                    'role' => $userData['role'],
                    'email_verified_at' => $userData['email_verified_at'] ? now() : null,
                ]
            );

            // Assign the appropriate role
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }

            $this->command->info(sprintf(
                'User created: %s (%s) - Role: %s - Verified: %s',
                $userData['email'],
                $userData['name'],
                $userData['role'],
                $userData['email_verified_at'] ? 'Yes' : 'No'
            ));
        }
    }
}

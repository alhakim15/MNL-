# Role and Permission System Documentation

## Overview
This document describes the updated role and permission system implemented for the Laravel 12 application.

## Roles and Permissions

### Admin Role
The admin role has full system access with the following permissions:
- `view_dashboard` - Access to admin dashboard
- `manage_users` - Create, edit, delete users
- `manage_deliveries` - Full delivery management
- `manage_ships` - Ship management
- `manage_cities` - City management
- `manage_infographics` - Infographic management
- `view_all_deliveries` - View all deliveries in system
- `create_delivery` - Create new deliveries
- `edit_delivery` - Edit any delivery
- `delete_delivery` - Delete deliveries
- `approve_delivery` - Approve pending deliveries
- `reject_delivery` - Reject deliveries

### User Role
The user role has limited access with the following permissions:
- `view_dashboard` - Access to user dashboard
- `view_own_deliveries` - View only their own deliveries
- `create_delivery` - Create new deliveries
- `edit_own_delivery` - Edit only their own deliveries
- `view_ships` - View available ships
- `view_cities` - View available cities
- `view_infographics` - View infographics

## Default Users Created

### Admin Users
1. **admin@mnl.com** (AdminUserSeeder)
   - Password: `password123`
   - Role: admin
   - Status: Email verified

2. **admin@admin.com** (UserSeeder)
   - Password: `password`
   - Role: admin
   - Status: Email verified

### Regular Users
1. **user@test.com** (AdminUserSeeder)
   - Password: `password123`
   - Role: user
   - Status: Email unverified

2. **user@example.com** (UserSeeder)
   - Password: `password`
   - Role: user
   - Status: Email verified

3. **unverified@example.com** (UserSeeder)
   - Password: `password`
   - Role: user
   - Status: Email unverified

## Laravel 12 Standards Implemented

### Code Quality
- ✅ Strict type declarations (`declare(strict_types=1);`)
- ✅ Proper type hints and return types
- ✅ Class constants for configuration
- ✅ Database transactions for data integrity
- ✅ WithoutModelEvents trait usage
- ✅ Comprehensive PHPDoc comments

### Security
- ✅ Automatic password hashing via model casting
- ✅ Proper guard name specification
- ✅ Permission cache clearing
- ✅ Email verification handling

### Architecture
- ✅ Separation of concerns with private methods
- ✅ Centralized permission definitions
- ✅ Transaction-wrapped operations
- ✅ Proper error handling and reporting

## Usage Examples

### Checking User Permissions
```php
// Check if user has specific permission
if ($user->can('manage_users')) {
    // User can manage users
}

// Check if user has role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check multiple permissions
if ($user->hasAnyPermission(['edit_delivery', 'delete_delivery'])) {
    // User can edit or delete deliveries
}
```

### Assigning Permissions in Controllers
```php
// Using middleware
public function __construct()
{
    $this->middleware('permission:manage_users')->only(['index', 'create', 'store']);
    $this->middleware('permission:edit_delivery')->only(['edit', 'update']);
}

// Using in methods
public function destroy(Delivery $delivery)
{
    $this->authorize('delete_delivery');
    // Delete logic
}
```

## Running Seeders

### Individual Seeders
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AdminUserSeeder
```

### All Seeders
```bash
php artisan db:seed
```

### Checking Current Permissions
```bash
php artisan permission:show
```

## Migration Requirements

Make sure you have published and run the Spatie Permission migrations:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

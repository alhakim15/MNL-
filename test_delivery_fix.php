<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Delivery;
use App\Models\City;
use App\Models\Ship;

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing delivery history persistence after profile update...\n\n";

// Create test user
$user = User::create([
    'first_name' => 'Test',
    'last_name' => 'User',
    'name' => 'Test User',
    'email' => 'test_' . time() . '@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);

echo "Created test user: {$user->name} (ID: {$user->id})\n";

// Get first city and ship for test delivery
$city = City::first();
$ship = Ship::first();

if (!$city || !$ship) {
    echo "Error: Need at least one city and one ship in database\n";
    exit(1);
}

// Create test delivery
$delivery = Delivery::create([
    'user_id' => $user->id,
    'sender_name' => $user->name,
    'receiver_name' => 'Test Receiver',
    'from_city_id' => $city->id,
    'to_city_id' => $city->id,
    'delivery_date' => now()->addDays(1),
    'item_name' => 'Test Item',
    'weight' => 1.5,
    'ship_id' => $ship->id,
    'resi' => 'TEST001',
    'shipping_cost' => 50000,
    'payment_status' => 'pending',
]);

echo "Created test delivery: {$delivery->resi} (ID: {$delivery->id})\n";

// Test: Get delivery history before profile update
$deliveriesBefore = Delivery::where('user_id', $user->id)->count();
echo "Delivery count BEFORE profile update: {$deliveriesBefore}\n";

// Update user profile (change name)
$user->update([
    'first_name' => 'Updated',
    'last_name' => 'Name',
    'name' => 'Updated Name',
]);

echo "Updated user profile: {$user->name}\n";

// Test: Get delivery history after profile update
$deliveriesAfter = Delivery::where('user_id', $user->id)->count();
echo "Delivery count AFTER profile update: {$deliveriesAfter}\n";

// Test: Check if the old way (sender_name) would fail
$deliveriesOldWay = Delivery::where('sender_name', $user->name)->count();
echo "Delivery count using old way (sender_name): {$deliveriesOldWay}\n";

// Cleanup
$delivery->delete();
$user->delete();

if ($deliveriesBefore === $deliveriesAfter && $deliveriesAfter > 0) {
    echo "\n✅ SUCCESS: Delivery history is preserved after profile update!\n";
} else {
    echo "\n❌ FAILED: Delivery history was not preserved!\n";
}

echo "\nTest completed.\n";

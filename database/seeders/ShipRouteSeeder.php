<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ship;
use App\Models\City;
use App\Models\ShipRoute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShipRouteSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Sample ship routes configuration.
     */
    private const SHIP_ROUTES = [
        'KM Lawit' => [
            ['from' => 'Jakarta', 'to' => 'Surabaya', 'distance' => 692, 'hours' => 18],
            ['from' => 'Surabaya', 'to' => 'Jakarta', 'distance' => 692, 'hours' => 18],
            ['from' => 'Jakarta', 'to' => 'Semarang', 'distance' => 445, 'hours' => 12],
            ['from' => 'Semarang', 'to' => 'Jakarta', 'distance' => 445, 'hours' => 12],
        ],
        'KM Dobonsolo' => [
            ['from' => 'Jakarta', 'to' => 'Pontianak', 'distance' => 710, 'hours' => 20],
            ['from' => 'Pontianak', 'to' => 'Jakarta', 'distance' => 710, 'hours' => 20],
            ['from' => 'Surabaya', 'to' => 'Makassar', 'distance' => 878, 'hours' => 24],
            ['from' => 'Makassar', 'to' => 'Surabaya', 'distance' => 878, 'hours' => 24],
        ],
        'KM Leuser' => [
            ['from' => 'Jakarta', 'to' => 'Batam', 'distance' => 460, 'hours' => 14],
            ['from' => 'Batam', 'to' => 'Jakarta', 'distance' => 460, 'hours' => 14],
            ['from' => 'Medan', 'to' => 'Jakarta', 'distance' => 1420, 'hours' => 36],
            ['from' => 'Jakarta', 'to' => 'Medan', 'distance' => 1420, 'hours' => 36],
        ],
        'KM Kelud' => [
            ['from' => 'Surabaya', 'to' => 'Denpasar', 'distance' => 345, 'hours' => 10],
            ['from' => 'Denpasar', 'to' => 'Surabaya', 'distance' => 345, 'hours' => 10],
            ['from' => 'Semarang', 'to' => 'Banjarmasin', 'distance' => 565, 'hours' => 16],
            ['from' => 'Banjarmasin', 'to' => 'Semarang', 'distance' => 565, 'hours' => 16],
        ],
        'KM Lambelu' => [
            ['from' => 'Makassar', 'to' => 'Balikpapan', 'distance' => 287, 'hours' => 8],
            ['from' => 'Balikpapan', 'to' => 'Makassar', 'distance' => 287, 'hours' => 8],
            ['from' => 'Surabaya', 'to' => 'Banjarmasin', 'distance' => 456, 'hours' => 14],
            ['from' => 'Banjarmasin', 'to' => 'Surabaya', 'distance' => 456, 'hours' => 14],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->createShipRoutes();
        });
    }

    /**
     * Create ship routes based on configuration.
     */
    private function createShipRoutes(): void
    {
        foreach (self::SHIP_ROUTES as $shipName => $routes) {
            // Find the ship
            $ship = Ship::where('name', $shipName)->first();

            if (!$ship) {
                $this->command->warn("Ship '{$shipName}' not found. Skipping routes.");
                continue;
            }

            $routesCreated = 0;

            foreach ($routes as $routeData) {
                $fromCity = City::where('name', $routeData['from'])->first();
                $toCity = City::where('name', $routeData['to'])->first();

                if (!$fromCity || !$toCity) {
                    $this->command->warn("Cities '{$routeData['from']}' or '{$routeData['to']}' not found. Skipping route.");
                    continue;
                }

                // Create the route
                $route = ShipRoute::firstOrCreate(
                    [
                        'ship_id' => $ship->id,
                        'origin_city_id' => $fromCity->id,
                        'destination_city_id' => $toCity->id,
                    ],
                    [
                        'distance_km' => $routeData['distance'],
                        'estimated_hours' => $routeData['hours'],
                        'is_active' => true,
                    ]
                );

                if ($route->wasRecentlyCreated) {
                    $routesCreated++;
                }
            }

            $this->command->info("Ship '{$shipName}': {$routesCreated} routes created.");
        }

        $totalRoutes = ShipRoute::count();
        $this->command->info("Total routes in system: {$totalRoutes}");
    }
}

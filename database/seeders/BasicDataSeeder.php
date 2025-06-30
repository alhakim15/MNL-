<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ship;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BasicDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Cities data.
     */
    private const CITIES = [
        'Jakarta',
        'Surabaya',
        'Semarang',
        'Pontianak',
        'Makassar',
        'Batam',
        'Medan',
        'Denpasar',
        'Banjarmasin',
        'Balikpapan',
    ];

    /**
     * Ships data.
     */
    private const SHIPS = [
        ['name' => 'KM Lawit', 'max_weight' => 1500],
        ['name' => 'KM Dobonsolo', 'max_weight' => 2000],
        ['name' => 'KM Leuser', 'max_weight' => 1200],
        ['name' => 'KM Kelud', 'max_weight' => 1800],
        ['name' => 'KM Lambelu', 'max_weight' => 1600],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->createCities();
            $this->createShips();
        });
    }

    /**
     * Create cities.
     */
    private function createCities(): void
    {
        $citiesCreated = 0;

        foreach (self::CITIES as $cityName) {
            $city = City::firstOrCreate(['name' => $cityName]);
            if ($city->wasRecentlyCreated) {
                $citiesCreated++;
            }
        }

        $this->command->info("Cities created: {$citiesCreated}");
    }

    /**
     * Create ships.
     */
    private function createShips(): void
    {
        $shipsCreated = 0;

        foreach (self::SHIPS as $shipData) {
            $ship = Ship::firstOrCreate(
                ['name' => $shipData['name']],
                ['max_weight' => $shipData['max_weight']]
            );
            if ($ship->wasRecentlyCreated) {
                $shipsCreated++;
            }
        }

        $this->command->info("Ships created: {$shipsCreated}");
    }
}

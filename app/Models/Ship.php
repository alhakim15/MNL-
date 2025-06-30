<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ship extends Model
{
    use HasFactory;

    protected $table = 'ships';

    protected $fillable = [
        'name',
        'max_weight',
    ];

    /**
     * Relasi ke pengiriman yang menggunakan kapal ini.
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'ship_id');
    }

    /**
     * Relasi ke rute yang bisa dilayani kapal ini.
     */
    public function routes(): HasMany
    {
        return $this->hasMany(ShipRoute::class);
    }

    /**
     * Get available cities this ship can serve as origin.
     */
    public function originCities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'ship_routes', 'ship_id', 'origin_city_id')
            ->where('is_active', true)
            ->distinct();
    }

    /**
     * Get available cities this ship can serve as destination.
     */
    public function destinationCities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'ship_routes', 'ship_id', 'destination_city_id')
            ->where('is_active', true)
            ->distinct();
    }

    /**
     * Check if ship can serve route from origin to destination.
     */
    public function canServeRoute(int $originCityId, int $destinationCityId): bool
    {
        return $this->routes()
            ->where('origin_city_id', $originCityId)
            ->where('destination_city_id', $destinationCityId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get available routes for this ship.
     */
    public function getAvailableRoutesAttribute(): array
    {
        return $this->routes()
            ->with(['originCity', 'destinationCity'])
            ->where('is_active', true)
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'route' => $route->originCity->name . ' → ' . $route->destinationCity->name,
                    'distance' => $route->distance_km . ' km',
                    'estimated_time' => $route->estimated_hours . ' jam',
                ];
            })
            ->toArray();
    }
}

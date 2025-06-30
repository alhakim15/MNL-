<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'ship_id',
        'origin_city_id',
        'destination_city_id',
        'distance_km',
        'estimated_hours',
        'is_active',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'estimated_hours' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Ship.
     */
    public function ship(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }

    /**
     * Relasi ke Origin City.
     */
    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    /**
     * Relasi ke Destination City.
     */
    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    /**
     * Scope untuk rute aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get route description.
     */
    public function getRouteDescriptionAttribute(): string
    {
        return $this->originCity->name . ' → ' . $this->destinationCity->name;
    }
}

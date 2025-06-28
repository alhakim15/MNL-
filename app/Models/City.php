<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $fillable = [
        'name',
        'image',
    ];

    public function departures()
    {
        return $this->hasMany(Delivery::class, 'from_city_id');
    }

    public function arrivals()
    {
        return $this->hasMany(Delivery::class, 'to_city_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            if ($city->image && Storage::disk('public')->exists($city->image)) {
                Storage::disk('public')->delete($city->image);
            }
        });
    }
}

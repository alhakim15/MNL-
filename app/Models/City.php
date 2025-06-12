<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

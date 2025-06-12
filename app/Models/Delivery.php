<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_name',
        'receiver_name',
        'from_city_id',
        'to_city_id',
        'delivery_date',
        'item_name',
        'ship_id',
        'weight',
    ];

    // Relasi ke kota asal
    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }

    // Relasi ke kota tujuan
    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }

    // Relasi ke kapal
    public function ship()
    {
        return $this->belongsTo(Ship::class, 'ship_id');
    }
}

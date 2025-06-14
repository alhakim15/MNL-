<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'ship_id');
    }
}

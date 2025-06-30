<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_name',
        'receiver_name',
        'from_city_id',
        'to_city_id',
        'delivery_date',
        'item_name',
        'ship_id',
        'weight',
        'resi',
        'shipping_cost',
        'payment_status',
        'payment_token',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'delivery_date' => 'date',
    ];

    const PAYMENT_STATUS = [
        'PENDING' => 'pending',
        'PAID' => 'paid',
        'FAILED' => 'failed',
        'EXPIRED' => 'expired',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function statusLogs()
    {
        return $this->hasMany(DeliveryStatusLog::class);
    }

    public function latestStatus()
    {
        return $this->hasOne(DeliveryStatusLog::class)->latestOfMany('logged_at');
    }
}

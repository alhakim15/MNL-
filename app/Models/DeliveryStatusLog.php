<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStatusLog extends Model
{

    use HasFactory;

    const STATUS = [
        'PENDING' => 'PENDING',
        'IN TRANSIT' => 'IN TRANSIT',
        'DELIVERED' => 'DELIVERED',
        'CANCELLED' => 'CANCELLED',
    ];
    protected $fillable = ['delivery_id', 'status', 'location', 'note', 'logged_at'];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}

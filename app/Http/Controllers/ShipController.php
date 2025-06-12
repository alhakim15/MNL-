<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use App\Models\Delivery;
use Illuminate\Http\Request;

class ShipController extends Controller
{

    public function checkCapacity($shipId)
    {
        $ship = Ship::with('deliveries')->findOrFail($shipId);
        $totalWeight = $ship->deliveries()->sum('weight');

        if ($totalWeight >= $ship->max_weight) {
            return response()->json(['status' => 'full', 'message' => 'Kapal sudah melebihi batas tonase.']);
        }

        return response()->json(['status' => 'available', 'message' => 'Kapal masih tersedia.']);
    }
}

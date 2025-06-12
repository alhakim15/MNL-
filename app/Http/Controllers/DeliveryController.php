<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Ship;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{

    public function create()
    {
        $cities = City::all();
        $ships = Ship::all();

        return view('deliverbook', compact('cities', 'ships'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name'    => 'required|string|max:255',
            'receiver_name'  => 'required|string|max:255',
            'from_city_id'   => 'required|exists:cities,id',
            'to_city_id'     => 'required|exists:cities,id',
            'delivery_date'  => 'required|date',
            'item_name'      => 'required|string|max:255',
            'weight'         => 'required|numeric|min:0.1',
            'ship_id'        => 'required|exists:ships,id',
        ]);

        // Ambil kapal yang dipilih
        $ship = Ship::findOrFail($validated['ship_id']);

        // Hitung total pengiriman aktif pada kapal tersebut
        $currentWeight = Delivery::where('ship_id', $ship->id)->sum('weight');

        // Jika total melebihi kapasitas kapal, tolak permintaan
        if ($currentWeight + $validated['weight'] > $ship->max_weight) {
            return back()->withErrors(['weight' => 'Kapasitas kapal melebihi batas maksimum (' . $ship->max_weight . ' ton).'])->withInput();
        }

        Delivery::create([
            'sender_name'    => $validated['sender_name'],
            'receiver_name'  => $validated['receiver_name'],
            'from_city_id'   => $validated['from_city_id'],
            'to_city_id'     => $validated['to_city_id'],
            'delivery_date'  => $validated['delivery_date'],
            'item_name'      => $validated['item_name'],
            'weight'         => $validated['weight'],
            'ship_id'        => $ship->id,
            'user_id'        => Auth::id(), // Simpan ID user yang membuat pengiriman
        ])->save();

        return redirect()->back()->with('success', 'Pengiriman berhasil dibuat!');
    }
}

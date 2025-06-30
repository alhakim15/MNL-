<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Ship;
use App\Models\Delivery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DeliveryStatusLog;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function create()
    {
        // Check email verification first
        if (is_null(auth()->user()->email_verified_at)) {
            return redirect()->route('verification.notice')->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk dapat menggunakan fitur pengiriman.');
        }

        $this->authorize('create', Delivery::class);
        $cities = City::all();
        $ships = Ship::withSum('deliveries', 'weight')->get()->map(function ($ship) {
            $ship->remaining_weight = $ship->max_weight - ($ship->deliveries_sum_weight ?? 0);
            return $ship;
        });

        return view('deliverbook', compact('cities', 'ships'));
    }



    public function store(Request $request)
    {
        // Check email verification first
        if (is_null(auth()->user()->email_verified_at)) {
            return redirect()->route('verification.notice')->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk dapat menggunakan fitur pengiriman.');
        }

        $this->authorize('create', Delivery::class);
        $request->validate([
            'receiver_name'  => 'required|string|max:255',
            'from_city_id'   => 'required|exists:cities,id',
            'to_city_id'     => 'required|exists:cities,id|different:from_city_id',
            'delivery_date'  => 'required|date|after_or_equal:today',
            'item_name'      => 'required|string|max:255',
            'weight'         => 'required|numeric|min:0.1',
            'ship_id'        => 'required|exists:ships,id',
        ]);

        $ship = Ship::findOrFail($request->ship_id);

        // Validate that ship can serve this route
        if (!$ship->canServeRoute($request->from_city_id, $request->to_city_id)) {
            return back()->withErrors([
                'ship_id' => 'Kapal yang dipilih tidak melayani rute dari ' .
                    City::find($request->from_city_id)->name . ' ke ' .
                    City::find($request->to_city_id)->name . '.'
            ])->withInput();
        }

        $currentWeight = Delivery::where('ship_id', $ship->id)->sum('weight');

        if ($currentWeight + $request->weight > $ship->max_weight) {
            return back()->withErrors(['weight' => 'Kapasitas kapal melebihi batas maksimum (' . $ship->max_weight . ' ton).'])->withInput();
        }

        // Calculate shipping cost
        $shippingCost = $this->midtransService->calculateShippingCost(
            $request->weight,
            $request->from_city_id,
            $request->to_city_id
        );

        $delivery = Delivery::create([
            'user_id'       => Auth::id(),
            'sender_name'   => Auth::user()->name,
            'receiver_name' => $request->receiver_name,
            'from_city_id'  => $request->from_city_id,
            'to_city_id'    => $request->to_city_id,
            'delivery_date' => $request->delivery_date,
            'item_name'     => $request->item_name,
            'weight'        => $request->weight,
            'ship_id'       => $ship->id,
            'resi'          => 'TRK' . strtoupper(Str::random(10)),
            'shipping_cost' => $shippingCost,
            'payment_status' => 'pending',
        ]);

        DeliveryStatusLog::create([
            'delivery_id' => $delivery->id,
            'status'      => DeliveryStatusLog::STATUS['PENDING'],
            'location'    => $delivery->fromCity->name ?? 'Lokasi asal',
            'note'        => 'Pengiriman baru saja dibuat.',
            'logged_at'   => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pengiriman berhasil dibuat!')
            ->with('deliveryData', [
                'resi' => $delivery->resi,
                'receiver_name' => $delivery->receiver_name,
                'from' => $delivery->fromCity->name,
                'to' => $delivery->toCity->name,
                'item' => $delivery->item_name,
                'weight' => $delivery->weight,
                'ship' => $delivery->ship->name,
                'date' => Carbon::parse($delivery->delivery_date)->format('d M Y'),
                'shipping_cost' => number_format($delivery->shipping_cost, 0, ',', '.'),
                'payment_url' => route('payment.show', $delivery->resi),
            ]);
    }

    public function history()
    {
        $deliveries = Delivery::with(['fromCity', 'toCity', 'ship', 'latestStatus'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('delivery-history', compact('deliveries'));
    }

    /**
     * Get available ships for selected route (AJAX endpoint).
     */
    public function getShipsByRoute(Request $request)
    {
        $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id' => 'required|exists:cities,id',
        ]);

        $fromCityId = $request->from_city_id;
        $toCityId = $request->to_city_id;

        // Get ships that can serve this route
        $ships = Ship::whereHas('routes', function ($query) use ($fromCityId, $toCityId) {
            $query->where('origin_city_id', $fromCityId)
                ->where('destination_city_id', $toCityId)
                ->where('is_active', true);
        })
            ->withSum('deliveries', 'weight')
            ->get()
            ->map(function ($ship) {
                $currentLoad = $ship->deliveries_sum_weight ?? 0;
                $remainingCapacity = $ship->max_weight - $currentLoad;

                return [
                    'id' => $ship->id,
                    'name' => $ship->name,
                    'max_weight' => $ship->max_weight,
                    'current_load' => $currentLoad,
                    'remaining_capacity' => $remainingCapacity,
                    'capacity_percentage' => round(($currentLoad / $ship->max_weight) * 100, 1),
                    'display_text' => $ship->name . ' (Tersisa: ' . $remainingCapacity . ' ton)',
                ];
            });

        // Get route information
        $route = \App\Models\ShipRoute::where('origin_city_id', $fromCityId)
            ->where('destination_city_id', $toCityId)
            ->where('is_active', true)
            ->with(['originCity', 'destinationCity'])
            ->first();

        return response()->json([
            'success' => true,
            'ships' => $ships,
            'route_info' => $route ? [
                'distance' => $route->distance_km,
                'estimated_hours' => $route->estimated_hours,
                'route_description' => $route->originCity->name . ' → ' . $route->destinationCity->name,
            ] : null,
            'message' => $ships->isEmpty()
                ? 'Tidak ada kapal yang melayani rute ini.'
                : $ships->count() . ' kapal tersedia untuk rute ini.',
        ]);
    }
}

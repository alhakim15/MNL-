<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $resi = $request->get('resi');

        if ($resi) {
            $delivery = Delivery::where('resi', $resi)->first();

            if ($delivery) {
                $logs = $delivery->statusLogs()->orderByDesc('logged_at')->get();
                return view('tracking.result', compact('delivery', 'logs'));
            }
        }

        return view('tracking.index', compact('resi'));
    }

    public function search(Request $request)
    {
        $request->validate(['resi' => 'required']);
        $delivery = Delivery::where('resi', $request->resi)->first();

        if (!$delivery) {
            return back()->with('error', 'Resi tidak ditemukan');
        }

        $logs = $delivery->statusLogs()->orderByDesc('logged_at')->get();

        return view('tracking.result', compact('delivery', 'logs'));
    }
}

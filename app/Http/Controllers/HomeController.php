<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Ship;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $city = City::all();
        $ships = Ship::all();
        return view('home', compact('city', 'ships'));
    }

    public function about()
    {
        return view('aboutus');
    }
}

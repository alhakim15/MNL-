<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Infographic;
use App\Models\Ship;
use Illuminate\Console\View\Components\Info;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $city = City::all();
        $ships = Ship::all();
        $infographics = Infographic::all();
        return view('home', compact('city', 'ships', 'infographics'));
    }

    public function about()
    {
        return view('aboutus');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
    $locations = Location::orderBy('created_at', 'desc')->paginate(6);
        return view('backend.locations.index', compact('locations'));
    }

    public function show(Location $location)
    {
        $temperature = null;
        if ($location->latitude && $location->longitude) {
            $apiKey = '566524ab9ba3b09a58018a14c8855340';
            $response = \Illuminate\Support\Facades\Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $location->latitude,
                'lon' => $location->longitude,
                'appid' => $apiKey,
                'units' => 'metric'
            ]);
            if ($response->successful()) {
                $temperature = $response->json('main.temp');
            }
        }
        return view('backend.locations.show', compact('location', 'temperature'));
    }

    // You can add create, store, edit, update, destroy as needed
}

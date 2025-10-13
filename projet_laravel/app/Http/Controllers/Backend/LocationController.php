<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
{
    $locations = Location::withCount('events')->orderBy('created_at', 'desc')->paginate(6);
    
    $allLocations = Location::all();
    $reservedCount = $allLocations->where('reserved', true)->count();
    $notReservedCount = $allLocations->where('reserved', false)->count();
    $inRepairCount = $allLocations->where('in_repair', true)->count();
    
    return view('backend.locations.index', compact('locations', 'allLocations', 'reservedCount', 'notReservedCount', 'inRepairCount'));
}

    public function show(Location $location)
    {
        $events = $location->events()->orderBy('date', 'desc')->paginate(4);
        return view('backend.locations.show', compact('location', 'events'));
    }

}

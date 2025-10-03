<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.locations.index', compact('locations'));
    }

    public function show(Location $location)
    {
        return view('backend.locations.show', compact('location'));
    }

    // You can add create, store, edit, update, destroy as needed
}

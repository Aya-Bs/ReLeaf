<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('in_repair', false)->where('reserved', false);
            } elseif ($request->status === 'in_repair') {
                $query->where('in_repair', true);
            } elseif ($request->status === 'reserved') {
                $query->where('reserved', true);
            }
        }

        // Apply sorting
        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->latest();
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } elseif ($request->sort === 'name') {
                $query->orderBy('name');
            }
        }

        $locations = $query->paginate(6);

        // Total counts
        $totalLocations = Location::count();
        $availableLocations = Location::where('in_repair', false)->where('reserved', false)->count();
        $inRepairLocations = Location::where('in_repair', true)->count();
        $reservedLocations = Location::where('reserved', true)->count();

        return view('frontend.location.index', compact('locations', 'totalLocations', 'availableLocations', 'inRepairLocations', 'reservedLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.location.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0', // Added validation for price
        ]);

    $data['in_repair'] = $request->has('in_repair');
    $data['latitude'] = $request->input('latitude');
    $data['longitude'] = $request->input('longitude');
    $data['price'] = $request->input('price'); // Assign price to data array

        // Handle images upload
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('locations', 'public');
            }
        }
        $data['images'] = $images;

        Location::create($data);
        return redirect()->route('locations.index')->with('success', 'Lieu ajouté avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $location = Location::findOrFail($id);
        $events = $location->events()->latest()->get();
        $temperature = null;
        $weather_icon = null;
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
                $weather = $response->json('weather');
                if (is_array($weather) && count($weather) > 0 && isset($weather[0]['icon'])) {
                    $weather_icon = $weather[0]['icon'];
                }
            }
        }
        return view('frontend.location.show', compact('location', 'events', 'temperature', 'weather_icon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('frontend.location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'address' => 'required|string',
            'city' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'in_repair' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image deletion
        if ($request->has('images_to_delete') && !empty($request->images_to_delete)) {
            $imagesToDelete = explode(',', $request->images_to_delete);
            $currentImages = $location->images ?? [];
            
            // Remove deleted images from storage and array
            foreach ($imagesToDelete as $imageToDelete) {
                if (($key = array_search($imageToDelete, $currentImages)) !== false) {
                    // Delete from storage
                    Storage::delete('public/' . $imageToDelete);
                    unset($currentImages[$key]);
                }
            }
            
            // Reindex array and update
            $validated['images'] = array_values($currentImages);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $currentImages = $validated['images'] ?? ($location->images ?? []);
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('locations', 'public');
                $currentImages[] = $path;
            }
            
            $validated['images'] = $currentImages;
        }

        $location->update($validated);

        return redirect()->route('locations.show', $location)
            ->with('success', 'Lieu mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Lieu supprimé avec succès!');
    }
}

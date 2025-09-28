<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Campaign;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('campaign')->latest();

        // Filtrage
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $resources = $query->paginate(15);
        $campaigns = Campaign::where('status', 'active')->get();

        // Statistiques
        $totalResources = Resource::count();
        $urgentResources = Resource::where('priority', 'urgent')->count();
        $completedResources = Resource::where('status', 'received')->count();
        $neededResources = Resource::where('status', 'needed')->count();

        return view('frontend.resources.index', compact(
            'resources', 
            'campaigns',
            'totalResources',
            'urgentResources',
            'completedResources',
            'neededResources'
        ));
    }

    public function create()
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('frontend.resources.create', compact('campaigns'));
    }

    public function store(StoreResourceRequest $request)
    {
        $validated = $request->validated();

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('resources', 'public');
        }

        Resource::create($validated);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource créée avec succès.');
    }

    public function show(Resource $resource)
    {
        $resource->load('campaign');
        return view('frontend.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('frontend.resources.edit', compact('resource', 'campaigns'));
    }

    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $validated = $request->validated();

        // Gestion de la suppression d'image
        if ($request->has('remove_image') && $resource->image_url) {
            Storage::disk('public')->delete($resource->image_url);
            $validated['image_url'] = null;
        }

        // Gestion du nouvel upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('resources', 'public');
        }

        $resource->update($validated);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(Resource $resource)
    {
        // Supprimer l'image associée
        if ($resource->image_url) {
            Storage::disk('public')->delete($resource->image_url);
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }

    public function updateStatus(Request $request, Resource $resource)
    {
        $request->validate([
            'status' => 'required|in:needed,pledged,received,in_use'
        ]);

        $resource->update(['status' => $request->status]);

        return back()->with('success', 'Statut de la ressource mis à jour.');
    }

    public function pledge(Request $request, Resource $resource)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'provider' => 'nullable|string|max:255'
        ]);

        $resource->increment('quantity_pledged', $request->quantity);

        if ($request->provider) {
            $resource->update(['provider' => $request->provider]);
        }

        // Mettre à jour le statut automatiquement
        $resource->updateStatus();

        return back()->with('success', 'Promesse de ressource enregistrée.');
    }

    public function highPriority()
    {
        $resources = Resource::whereIn('priority', ['high', 'urgent'])
                            ->with('campaign')
                            ->latest()
                            ->paginate(15);

        return view('frontend.resources.high-priority', compact('resources'));
    }
}
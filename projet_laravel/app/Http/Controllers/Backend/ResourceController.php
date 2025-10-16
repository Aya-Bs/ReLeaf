<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceRequest;
use App\Models\Campaign;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('campaign')->latest();

        // Filtrage
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        // ✅ CORRIGÉ : Même pattern que EventController et CampaignController
        if (! $resource->canBeEdited()) {
            return redirect()->route('resources.index')->with('error', 'Cette ressource ne peut pas être modifiée.');
        }

        $campaigns = Campaign::where('status', 'active')->get();

        return view('frontend.resources.edit', compact('resource', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        // ✅ CORRIGÉ : Même pattern que EventController et CampaignController
        if (! $resource->canBeEdited()) {
            return redirect()->route('resources.index')->with('error', 'Cette ressource ne peut pas être modifiée.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'provider' => 'nullable|string|max:255',
            'quantity_needed' => 'required|integer|min:1',
            'quantity_pledged' => 'nullable|integer|min:0',
            'unit' => 'required|string|max:50',
            'resource_type' => 'required|in:money,food,clothing,medical,equipment,human,other',
            'category' => 'required|in:materiel,financier,humain,technique',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:needed,pledged,received,in_use',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Préparer les données pour la mise à jour
        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'campaign_id' => $request->campaign_id,
            'provider' => $request->provider,
            'quantity_needed' => $request->quantity_needed,
            'quantity_pledged' => $request->quantity_pledged ?? 0,
            'unit' => $request->unit,
            'resource_type' => $request->resource_type,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        // Gestion de la suppression d'image
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }
            $updateData['image_url'] = null;
        }

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($resource->image_url) {
                Storage::disk('public')->delete($resource->image_url);
            }
            $updateData['image_url'] = $request->file('image')->store('resources', 'public');
        }

        $resource->update($updateData);

        return redirect()->route('resources.show', $resource)
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(Resource $resource)
    {
        // ✅ CORRIGÉ : Même pattern
        if (! $resource->canBeEdited()) {
            return redirect()->route('resources.index')->with('error', 'Cette ressource ne peut pas être supprimée.');
        }

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
            'status' => 'required|in:needed,pledged,received,in_use',
        ]);

        $resource->update(['status' => $request->status]);

        return back()->with('success', 'Statut de la ressource mis à jour.');
    }

    public function pledge(Request $request, Resource $resource)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'provider' => 'nullable|string|max:255',
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

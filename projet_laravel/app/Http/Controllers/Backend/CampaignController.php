<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('organizer', 'resources', 'events')
                            ->latest()
                            ->paginate(10);
        
        return view('frontend.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        if (!Auth::user()->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }
        return view('frontend.campaigns.create');
    }

    public function store(StoreCampaignRequest $request)
    {
        $validated = $request->validated();
        $validated['organizer_id'] = Auth::id();
        
        // Convertir les tags de string en array
        if (isset($validated['tags']) && is_string($validated['tags'])) {
            $validated['tags'] = $this->parseTags($validated['tags']);
        }
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('resources', 'events', /*'donations',*/ 'organizer');
        return view('frontend.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        // ✅ CORRIGÉ : Même pattern que EventController
        if ($campaign->organizer_id !== Auth::id()) {
            return redirect()->route('campaigns.index')->with('error', 'Cette campagne ne peut pas être modifiée.');
        }

        return view('frontend.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        // ✅ CORRIGÉ : Même pattern que EventController
        if ($campaign->organizer_id !== Auth::id()) {
            return redirect()->route('campaigns.index')->with('error', 'Cette campagne ne peut pas être modifiée.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'goal' => 'nullable|numeric|min:0',
            'funds_raised' => 'nullable|numeric|min:0',
            'environmental_impact' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visibility' => 'nullable|boolean',
        ]);

        // Préparer les données pour la mise à jour
        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'goal' => $request->goal,
            'funds_raised' => $request->funds_raised,
            'environmental_impact' => $request->environmental_impact,
            'visibility' => $request->has('visibility') ? true : false,
        ];

        // Convertir les tags de string en array
        if ($request->has('tags') && is_string($request->tags)) {
            $updateData['tags'] = $this->parseTags($request->tags);
        }

        // Gestion de la suppression d'image
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }
            $updateData['image_url'] = null;
        }

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }
            $updateData['image_url'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($updateData);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campagne mise à jour avec succès.');
    }

    /**
     * Convertir une chaîne de tags en tableau
     */
    private function parseTags($tagsString)
    {
        if (empty(trim($tagsString))) {
            return null;
        }

        $tags = explode(',', $tagsString);
        $tags = array_map('trim', $tags);
        $tags = array_filter($tags); // Supprimer les éléments vides
        
        return empty($tags) ? null : $tags;
    }

    public function destroy(Campaign $campaign)
    {
        // ✅ CORRIGÉ : Même pattern
        if ($campaign->organizer_id !== Auth::id() || !$campaign->canBeEdited()) {
            return redirect()->route('campaigns.index')->with('error', 'Cette campagne ne peut pas être supprimée.');
        }

        // Supprimer l'image associée
        if ($campaign->image_url) {
            Storage::disk('public')->delete($campaign->image_url);
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne supprimée avec succès.');
    }

    // Méthode pour mettre à jour la visibilité
    public function toggleVisibility(Campaign $campaign)
    {
        $campaign->update(['visibility' => !$campaign->visibility]);

        return back()->with('success', 'Visibilité mise à jour.');
    }

    // Méthode pour les statistiques
    public function statistics()
    {
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::active()->count();
        $totalFunds = Campaign::sum('funds_raised');
        $totalParticipants = Campaign::sum('participants_count');

        $campaignsByCategory = Campaign::groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->get();

        return view('frontend.campaigns.statistics', compact(
            'totalCampaigns',
            'activeCampaigns',
            'totalFunds',
            'totalParticipants',
            'campaignsByCategory'
        ));
    }
}
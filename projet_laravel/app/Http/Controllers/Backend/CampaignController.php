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
        // Vérifier que l'utilisateur peut éditer cette campagne
        if (Auth::id() !== $campaign->organizer_id) {
            abort(403, "Vous n'êtes pas autorisé à modifier cette campagne.");
        }

        return view('frontend.campaigns.edit', compact('campaign'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        // Vérifier que l'utilisateur est le créateur de la campagne OU un admin
        if (Auth::id() !== $campaign->organizer_id) {
            abort(403, "Vous n'êtes pas autorisé à modifier cette campagne.");
        }

        $validated = $request->validated();
        
        // Convertir les tags de string en array
        if (isset($validated['tags']) && is_string($validated['tags'])) {
            $validated['tags'] = $this->parseTags($validated['tags']);
        }

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('campaigns.index')
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
        // Vérifier les autorisations
        if (Auth::id() !== $campaign->organizer_id) {
            abort(403, "Vous n'êtes pas autorisé à supprimer cette campagne.");
        }

        // Supprimer l'image associée
        if ($campaign->image_url) {
            Storage::disk('public')->delete($campaign->image_url);
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')  // ✅ Corrigé : 'campaigns.index' au lieu de 'frontend.campaigns.index'
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
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\Request;
use App\Models\CampaignDeletionRequest;
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

    /**
     * Display all campaigns (for users browsing all public campaigns).
     */
    public function all()
    {
        $campaigns = Campaign::with('organizer', 'resources', 'events')
            ->orderBy('start_date', 'desc')
            ->paginate(18);

        return view('frontend.campaigns.all', compact('campaigns'));
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

    /**
     * Get featured campaigns for home page hero section
     */
    public function featuredCampaigns()
    {
        $featuredCampaigns = Campaign::where('visibility', true)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('organizer')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return $featuredCampaigns;
    }

    // Ajouter ces méthodes dans CampaignController.php

    public function requestDeletion(Request $request, Campaign $campaign)
    {
        // Vérifier si l'utilisateur peut faire la demande
        if ($campaign->organizer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas demander la suppression de cette campagne.');
        }

        // Vérifier s'il y a déjà une demande en attente
        if ($campaign->pendingDeletionRequest) {
            return redirect()->back()->with('warning', 'Une demande de suppression est déjà en attente.');
        }

        // Créer la demande de suppression
        CampaignDeletionRequest::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Demande de suppression envoyée. En attente de confirmation par l\'administrateur.');
    }

    public function deletionRequests()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $deletionRequests = CampaignDeletionRequest::with(['campaign', 'user'])
            ->pending()
            ->latest()
            ->paginate(10);

        return view('backend.campaigns.index', compact('deletionRequests'));
    }

    public function processDeletionRequest(Request $request, CampaignDeletionRequest $deletionRequest)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            // Supprimer la campagne
            $campaign = $deletionRequest->campaign;

            // Supprimer l'image associée
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }

            $campaign->delete();

            // Mettre à jour la demande
            $deletionRequest->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'admin_notes' => $request->admin_notes,
                'processed_at' => now()
            ]);

            return redirect()->route('backend.campaigns.deletion-requests')
                ->with('success', 'Demande de suppression approuvée et campagne supprimée.');
        } else {
            // Rejeter la demande
            $deletionRequest->update([
                'status' => 'rejected',
                'processed_by' => Auth::id(),
                'admin_notes' => $request->admin_notes,
                'processed_at' => now()
            ]);

            return redirect()->route('backend.campaigns.deletion-requests')
                ->with('success', 'Demande de suppression rejetée.');
        }
    }
}

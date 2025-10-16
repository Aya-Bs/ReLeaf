<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil EcoEvents.
     */
    public function index(): View
    {
        // Récupérer les statistiques pour le dashboard
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_events' => 0, // À corriger après migration
            'eco_ambassadors' => User::whereHas('profile', function ($query) {
                $query->where('is_eco_ambassador', true);
            })->count(),
        ];

        // Logique selon le rôle de l'utilisateur
        $query = Event::where('status', 'published')
            ->where('date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(12); // Get 12 events for 3 slides of 4

        if (auth()->check()) {
            if (auth()->user()->role === 'organizer') {
                // Les organisateurs voient seulement leurs propres événements
                $query->where('user_id', auth()->id());
            }
            // Les utilisateurs avec rôle 'user' voient tous les événements
        }

        $recentEvents = $query->get();

        // Récupérer les ambassadeurs écologiques
        $ecoAmbassadors = User::whereHas('profile', function ($query) {
            $query->where('is_eco_ambassador', true);
        })->with('profile')->limit(6)->get();

        // ✅ NOUVEAU : Campagnes en vedette pour le hero
        $featuredCampaigns = Campaign::where('visibility', true)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('organizer')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return view('frontend.home', compact('stats', 'recentEvents', 'ecoAmbassadors', 'featuredCampaigns'));
    }

    /**
     * Afficher la page À propos.
     */
    public function about(): View
    {
        return view('frontend.about');
    }

    /**
     * Afficher la page Contact.
     */
    public function contact(): View
    {
        return view('frontend.contact');
    }
}

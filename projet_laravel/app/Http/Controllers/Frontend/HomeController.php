<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Campaign;
use App\Services\SponsorRewardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Landing page accessible to guests with home-like features.
     */
    public function landing(SponsorRewardService $rewards): View
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_events' => 0,
            'eco_ambassadors' => User::whereHas('profile', function ($query) {
                $query->where('is_eco_ambassador', true);
            })->count(),
        ];

        $recentEvents = Event::where('status', 'published')
            ->where('date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        $ecoAmbassadors = User::whereHas('profile', function ($query) {
            $query->where('is_eco_ambassador', true);
        })->with('profile')->limit(6)->get();

        $featuredCampaigns = Campaign::where('visibility', true)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('organizer')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        $topSponsors = $rewards->topSponsors(10, 90);
        return view('frontend.landing', compact('stats', 'recentEvents', 'ecoAmbassadors', 'featuredCampaigns', 'topSponsors'));
    }
    /**
     * Afficher la page d'accueil EcoEvents.
     */
    public function index(SponsorRewardService $rewards): View
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

        if (Auth::check()) {
            if (Auth::user()->role === 'organizer') {
                // Les organisateurs voient seulement leurs propres événements
                $query->where('user_id', Auth::id());
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

        $topSponsors = $rewards->topSponsors(10, 90);
        return view('frontend.home', compact('stats', 'recentEvents', 'ecoAmbassadors', 'featuredCampaigns', 'topSponsors'));
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

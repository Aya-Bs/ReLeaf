<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
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
            'eco_ambassadors' => User::whereHas('profile', function($query) {
                $query->where('is_eco_ambassador', true);
            })->count(),
        ];

       $recentEvents = Event::where('status', 'published')
        ->where('date', '>=', now())
        ->orderBy('created_at', 'desc')
        ->take(12) // Get 12 events for 3 slides of 4
        ->get();


        // Récupérer les ambassadeurs écologiques
        $ecoAmbassadors = User::whereHas('profile', function($query) {
            $query->where('is_eco_ambassador', true);
        })->with('profile')->limit(6)->get();

        return view('frontend.home', compact('stats', 'recentEvents', 'ecoAmbassadors'));
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Afficher le tableau de bord d'administration.
     */
    public function dashboard(): View
    {
        // Statistiques globales
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_events' => 0, // À corriger après migration
            'eco_ambassadors' => User::whereHas('profile', function ($query) {
                $query->where('is_eco_ambassador', true);
            })->count(),
            'total_volunteers' => Volunteer::count(),
            'active_volunteers' => Volunteer::where('status', 'active')->count(),
            'total_assignments' => Assignment::count(),
            'pending_assignments' => Assignment::where('status', 'pending')->count(),
            'recent_users' => User::with('profile')
                ->where('role', 'user')
                ->latest()
                ->limit(5)
                ->get(),
            'recent_volunteers' => Volunteer::with('user')
                ->latest()
                ->limit(5)
                ->get(),
        ];

        // Graphiques (données fictives pour le moment)
        $chartData = [
            'users_by_month' => [
                'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                'data' => [12, 19, 15, 25, 22, 30],
            ],
            'events_by_category' => [
                'labels' => ['Environnement', 'Recyclage', 'Énergie', 'Transport'],
                'data' => [35, 25, 20, 20],
            ],
        ];

        return view('backend.dashboard', compact('stats', 'chartData'));
    }

    /**
     * Afficher la page de bienvenue admin.
     */
    public function welcome(): View
    {
        $welcomeStats = [
            'today_users' => User::whereDate('created_at', today())->count(),
            'pending_tasks' => 0, // À définir selon les besoins
            'system_health' => 'Excellent',
        ];

        return view('backend.welcome', compact('welcomeStats'));
    }
}

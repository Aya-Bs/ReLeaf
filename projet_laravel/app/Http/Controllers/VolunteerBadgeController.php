<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VolunteerBadgeController extends Controller
{
    /**
     * Generate volunteer badge PDF
     */
    public function generateBadge(Volunteer $volunteer)
    {
        // Vérifier que l'utilisateur peut accéder à ce badge
        if (Auth::id() !== $volunteer->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à ce badge.');
        }

        // Vérifier que le volontaire est approuvé
        if (!$volunteer->isApproved()) {
            abort(403, 'Ce volontaire n\'est pas encore approuvé.');
        }

        // Calculer l'âge
        $age = $volunteer->user->date_of_birth ? 
            Carbon::parse($volunteer->user->date_of_birth)->age : 
            'Non spécifié';

        // Préparer les données pour le badge
        $badgeData = [
            'volunteer' => $volunteer,
            'user' => $volunteer->user,
            'age' => $age,
            'generated_at' => now()->format('d/m/Y H:i'),
            'badge_id' => 'RELEAF-' . str_pad($volunteer->id, 6, '0', STR_PAD_LEFT),
            'skills' => $volunteer->skills ?? [],
            'regions' => $volunteer->preferred_regions ?? [],
            'experience_level' => ucfirst($volunteer->experience_level),
            'max_hours' => $volunteer->max_hours_per_week,
            'emergency_contact' => $volunteer->emergency_contact,
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('volunteers.badge', $badgeData);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Badge_Volontaire_' . $volunteer->user->name . '.pdf');
    }

    /**
     * Show volunteer badge preview
     */
    public function showBadge(Volunteer $volunteer)
    {
        // Vérifier que l'utilisateur peut accéder à ce badge
        if (Auth::id() !== $volunteer->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à ce badge.');
        }

        // Vérifier que le volontaire est approuvé
        if (!$volunteer->isApproved()) {
            abort(403, 'Ce volontaire n\'est pas encore approuvé.');
        }

        // Calculer l'âge
        $age = $volunteer->user->date_of_birth ? 
            Carbon::parse($volunteer->user->date_of_birth)->age : 
            'Non spécifié';

        // Préparer les données pour le badge
        $badgeData = [
            'volunteer' => $volunteer,
            'user' => $volunteer->user,
            'age' => $age,
            'generated_at' => now()->format('d/m/Y H:i'),
            'badge_id' => 'RELEAF-' . str_pad($volunteer->id, 6, '0', STR_PAD_LEFT),
            'skills' => $volunteer->skills ?? [],
            'regions' => $volunteer->preferred_regions ?? [],
            'experience_level' => ucfirst($volunteer->experience_level),
            'max_hours' => $volunteer->max_hours_per_week,
            'emergency_contact' => $volunteer->emergency_contact,
        ];

        return view('volunteers.badge-preview', $badgeData);
    }
}
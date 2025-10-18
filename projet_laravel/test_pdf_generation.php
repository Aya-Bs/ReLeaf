<?php

require_once 'vendor/autoload.php';

use App\Models\Volunteer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Test de génération PDF...\n";

try {
    // Trouver un volontaire approuvé
    $volunteer = Volunteer::where('approval_status', 'approved')->with('user')->first();
    
    if (!$volunteer) {
        echo "❌ Aucun volontaire approuvé trouvé.\n";
        exit(1);
    }
    
    echo "✅ Volontaire trouvé: {$volunteer->user->email}\n";
    
    // Calculer l'âge
    $age = $volunteer->user->date_of_birth ? 
        Carbon::parse($volunteer->user->date_of_birth)->age : 
        'Non spécifié';
    
    // Préparer les données
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
    
    echo "✅ Données préparées\n";
    
    // Générer le PDF
    $pdf = Pdf::loadView('volunteers.badge', $badgeData);
    $pdf->setPaper('A4', 'portrait');
    
    echo "✅ PDF généré avec succès\n";
    
    // Sauvegarder le PDF pour test
    $filename = 'test_badge_' . time() . '.pdf';
    $pdf->save(public_path($filename));
    
    echo "✅ PDF sauvegardé: {$filename}\n";
    echo "🎉 Test réussi!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

@extends('layouts.frontend')

@section('title', 'Badge Volontaire')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- En-tête -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>Mon Badge Volontaire
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5>{{ $user->full_name }}</h5>
                            <p class="text-muted mb-0">Badge ID: {{ $badge_id }}</p>
                            <small class="text-muted">Généré le {{ $generated_at }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('volunteers.badge.download', $volunteer) }}" class="btn btn-success me-2">
                                <i class="fas fa-download me-1"></i>Télécharger PDF
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-success">
                                <i class="fas fa-print me-1"></i>Imprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Badge Preview -->
            <div class="card">
                <div class="card-body p-0">
                    <div id="badge-preview">
                        <!-- Le contenu du badge sera inséré ici -->
                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; color: white; text-align: center; position: relative;">
                            <div style="position: absolute; top: 20px; right: 30px; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 20px; font-size: 0.9em; font-weight: bold;">
                                {{ $badge_id }}
                            </div>
                            <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 10px;">ReLeaf</div>
                            <div style="font-size: 1.2em; opacity: 0.9;">Carte d'Identité Volontaire</div>
                        </div>
                        
                        <div style="padding: 40px;">
                            <div style="display: flex; margin-bottom: 30px; align-items: center;">
                                <img src="{{ $user->avatar_url }}" alt="Photo" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid #28a745; margin-right: 30px; object-fit: cover;">
                                <div>
                                    <h2 style="color: #28a745; font-size: 2em; margin-bottom: 10px;">{{ $user->full_name }}</h2>
                                    <p style="color: #666; font-size: 1.1em; margin-bottom: 5px;"><strong>Âge:</strong> {{ $age }} ans</p>
                                    <p style="color: #666; font-size: 1.1em; margin-bottom: 5px;"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p style="color: #666; font-size: 1.1em; margin-bottom: 5px;"><strong>Téléphone:</strong> +216 {{ $emergency_contact }}</p>
                                    <div style="display: inline-block; background: #28a745; color: white; padding: 8px 20px; border-radius: 25px; font-weight: bold; margin-top: 10px;">Volontaire Approuvé</div>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #28a745;">
                                    <h3 style="color: #28a745; margin-bottom: 15px; font-size: 1.2em;">Informations Professionnelles</h3>
                                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <span style="font-weight: bold; color: #333;">Niveau d'expérience:</span>
                                        <span style="color: #666;">{{ $experience_level }}</span>
                                    </div>
                                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <span style="font-weight: bold; color: #333;">Heures max/semaine:</span>
                                        <span style="color: #666;">{{ $max_hours }}h</span>
                                    </div>
                                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <span style="font-weight: bold; color: #333;">Statut:</span>
                                        <span style="color: #666;">Actif</span>
                                    </div>
                                </div>
                                
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #28a745;">
                                    <h3 style="color: #28a745; margin-bottom: 15px; font-size: 1.2em;">Zones d'Intervention</h3>
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        @foreach($regions as $region)
                                            <span style="background: #20c997; color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.9em;">{{ $region }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                                <h3 style="color: #28a745; margin-bottom: 15px;">Compétences</h3>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @foreach($skills as $skill)
                                        <span style="background: #28a745; color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.9em;">{{ ucfirst($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div style="text-align: center; margin-top: 30px;">
                                <div style="width: 80px; height: 80px; background: #ddd; border: 2px solid #28a745; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 0.8em; color: #666;">
                                    QR Code<br>{{ $badge_id }}
                                </div>
                                <p style="color: #666; font-size: 0.9em;">
                                    <strong>Badge généré le:</strong> {{ $generated_at }}<br>
                                    <strong>Valide jusqu'au:</strong> {{ now()->addYear()->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div style="background: #f8f9fa; padding: 20px; text-align: center; border-top: 2px solid #28a745;">
                            <p style="color: #666; font-size: 0.9em;">
                                Ce badge certifie que <strong>{{ $user->full_name }}</strong> est un volontaire officiel de ReLeaf.<br>
                                En cas de problème, contactez-nous à: contact@releaf.tn
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, .container .row:first-child {
        display: none !important;
    }
    
    #badge-preview {
        box-shadow: none !important;
        border: none !important;
    }
    
    body {
        background: white !important;
    }
}
</style>
@endsection

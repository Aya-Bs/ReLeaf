<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badge Volontaire - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .badge-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .badge-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .badge-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0.3;
        }
        
        .logo {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .badge-title {
            font-size: 1.2em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .badge-id {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .badge-content {
            padding: 40px;
        }
        
        .volunteer-info {
            display: flex;
            margin-bottom: 30px;
            align-items: center;
        }
        
        .volunteer-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #28a745;
            margin-right: 30px;
            object-fit: cover;
        }
        
        .volunteer-details h2 {
            color: #28a745;
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .volunteer-details p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }
        
        .info-section h3 {
            color: #28a745;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        
        .info-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        .info-value {
            color: #666;
        }
        
        .skills-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .skills-section h3 {
            color: #28a745;
            margin-bottom: 15px;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .skill-tag {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .regions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .region-tag {
            background: #20c997;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .badge-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 2px solid #28a745;
        }
        
        .footer-text {
            color: #666;
            font-size: 0.9em;
        }
        
        
        .status-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="badge-container">
        <!-- En-tête du badge -->
        <div class="badge-header">
            <div class="badge-id">{{ $badge_id }}</div>
            <div class="logo">ReLeaf</div>
            <div class="badge-title">Carte d'Identité Volontaire</div>
        </div>
        
        <!-- Contenu du badge -->
        <div class="badge-content">
            <!-- Informations du volontaire -->
            <div class="volunteer-info">
                <img src="{{ $user->avatar_url }}" alt="Photo" class="volunteer-photo">
                <div class="volunteer-details">
                    <h2>{{ $user->full_name }}</h2>
                    <p><strong>Âge:</strong> {{ $age }} ans</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Téléphone:</strong> +216 {{ $emergency_contact }}</p>
                    <div class="status-badge">Volontaire Approuvé</div>
                </div>
            </div>
            
            <!-- Grille d'informations -->
            <div class="info-grid">
                <div class="info-section">
                    <h3>Informations Professionnelles</h3>
                    <div class="info-item">
                        <span class="info-label">Niveau d'expérience:</span>
                        <span class="info-value">{{ $experience_level }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Heures max/semaine:</span>
                        <span class="info-value">{{ $max_hours }}h</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Statut:</span>
                        <span class="info-value">Actif</span>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3>Zones d'Intervention</h3>
                    <div class="regions-list">
                        @foreach($regions as $region)
                            <span class="region-tag">{{ $region }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Compétences -->
            <div class="skills-section">
                <h3>Compétences</h3>
                <div class="skills-list">
                    @foreach($skills as $skill)
                        <span class="skill-tag">{{ ucfirst($skill) }}</span>
                    @endforeach
                </div>
            </div>
            
            <!-- Informations de validation -->
            <div style="text-align: center; margin-top: 30px;">
                <p class="footer-text">
                    <strong>Badge généré le:</strong> {{ $generated_at }}<br>
                    <strong>Valide jusqu'au:</strong> {{ now()->addYear()->format('d/m/Y') }}
                </p>
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="badge-footer">
            <p class="footer-text">
                Ce badge certifie que <strong>{{ $user->full_name }}</strong> est un volontaire officiel de ReLeaf.<br>
                En cas de problème, contactez-nous à: contact@releaf.tn
            </p>
        </div>
    </div>
</body>
</html>

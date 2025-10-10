<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Participation - EcoEvents</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
        }
        
        .certificate {
            width: 100%;
            height: 100vh;
            background: white;
            border: 10px solid #2d5a27;
            position: relative;
            box-sizing: border-box;
        }
        
        /* Bordures décoratives aux coins */
        .corner-top-left {
            position: absolute;
            top: 25px;
            left: 25px;
            width: 50px;
            height: 50px;
            border-top: 5px solid #2d5a27;
            border-left: 5px solid #2d5a27;
        }
        
        .corner-top-right {
            position: absolute;
            top: 25px;
            right: 25px;
            width: 50px;
            height: 50px;
            border-top: 5px solid #2d5a27;
            border-right: 5px solid #2d5a27;
        }
        
        .corner-bottom-left {
            position: absolute;
            bottom: 25px;
            left: 25px;
            width: 50px;
            height: 50px;
            border-bottom: 5px solid #2d5a27;
            border-left: 5px solid #2d5a27;
        }
        
        .corner-bottom-right {
            position: absolute;
            bottom: 25px;
            right: 25px;
            width: 50px;
            height: 50px;
            border-bottom: 5px solid #2d5a27;
            border-right: 5px solid #2d5a27;
        }
        
        .certificate-content {
            padding: 80px 60px 60px;
            height: calc(100vh - 160px);
            text-align: center;
            position: relative;
        }
        
        .header {
            margin-bottom: 60px;
        }
        
        .logo {
            font-size: 60px;
            color: #2d5a27;
            margin-bottom: 25px;
            font-weight: bold;
            display: none;
        }
        
        .title {
            font-size: 42px;
            font-weight: bold;
            color: #2d5a27;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        
        .subtitle {
            font-size: 20px;
            color: #666;
            margin-bottom: 50px;
        }
        
        .main-content {
            margin: 50px 0;
        }
        
        .certificate-text {
            font-size: 22px;
            margin-bottom: 25px;
            line-height: 1.6;
            color: #333;
        }
        
        .participant-name {
            font-size: 36px;
            font-weight: bold;
            color: #2d5a27;
            margin: 30px 0;
            text-decoration: underline;
            text-decoration-color: #2d5a27;
        }
        
        .event-details {
            background: #f8f9fa;
            border: 4px solid #e9ecef;
            padding: 40px;
            margin: 40px auto;
            width: 85%;
            max-width: 700px;
        }
        
        .event-title {
            font-size: 26px;
            font-weight: bold;
            color: #2d5a27;
            margin-bottom: 25px;
            line-height: 1.3;
        }
        
        .event-info {
            margin: 25px 0;
        }
        
        .event-info-item {
            margin: 15px 0;
            text-align: center;
        }
        
        .event-info-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
        }
        
        .event-info-value {
            font-size: 18px;
            font-weight: bold;
            color: #2d5a27;
        }
        
        .verification-section {
            background: #e8f5e8;
            border: 4px dashed #2d5a27;
            padding: 30px;
            margin: 40px auto;
            width: 75%;
            max-width: 500px;
        }
        
        .verification-label {
            font-size: 16px;
            color: #666;
            margin-bottom: 12px;
            font-weight: bold;
        }
        
        .verification-value {
            font-size: 22px;
            font-weight: bold;
            color: #2d5a27;
            font-family: monospace;
        }
        
        .certificate-footer {
            margin-top: 50px;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 4px solid #2d5a27;
            width: 300px;
            margin: 25px auto 15px;
            height: 40px;
        }
        
        .signature-text {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        
        .date-issued {
            font-size: 16px;
            color: #666;
            margin-top: 25px;
        }
        
        /* Élément décoratif central */
        .decorative-center {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -125px;
            margin-left: -125px;
            width: 250px;
            height: 250px;
            border: 4px solid #2d5a27;
            opacity: 0.05;
        }
        
        .decorative-center::before {
            content: '🌱';
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -25px;
            font-size: 100px;
            opacity: 0.1;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Bordures décoratives aux coins -->
        <div class="corner-top-left"></div>
        <div class="corner-top-right"></div>
        <div class="corner-bottom-left"></div>
        <div class="corner-bottom-right"></div>
        
        <!-- Élément décoratif central -->
        <div class="decorative-center"></div>
        
        <div class="certificate-content">
            <div class="header">
                <div class="title">EcoEvents</div>
                <div class="subtitle">Certificat de Participation</div>
            </div>
            
            <div class="main-content">
                <div class="certificate-text">
                    Ce certificat atteste que
                </div>
                
                <div class="participant-name">
                    {{ $certification->reservation->user->name }}
                </div>
                
                <div class="certificate-text">
                    a participé avec succès à l'événement suivant :
                </div>
                
                <div class="event-details">
                    <div class="event-title">{{ $certification->reservation->event->title }}</div>
                    
                    <div class="event-info">
                        <div class="event-info-item">
                            <div class="event-info-label">Date</div>
                            <div class="event-info-value">{{ $certification->reservation->event->date->format('d/m/Y') }}</div>
                        </div>
                        <div class="event-info-item">
                            <div class="event-info-label">Heure</div>
                            <div class="event-info-value">{{ $certification->reservation->event->date->format('H:i') }}</div>
                        </div>
                        <div class="event-info-item">
                            <div class="event-info-label">Lieu</div>
                            <div class="event-info-value">{{ $certification->reservation->event->location->name ?? 'Lieu non défini' }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="verification-section">
                    <div class="verification-label">Code de Vérification</div>
                    <div class="verification-value">{{ $certification->certificate_code }}</div>
                </div>
            </div>
            
            <div class="certificate-footer">
                <div class="signature-line"></div>
                <div class="signature-text">
                    {{ $certification->issuedBy->name }}<br>
                    Organisateur EcoEvents
                </div>
                <div class="date-issued">
                    Délivré le {{ $certification->date_awarded->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat Accordé - EcoEvents</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2d5a27;
        }
        .logo {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .title {
            color: #2d5a27;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2d5a27;
        }
        .message {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .certificate-info {
            background: #f8f9fa;
            border: 2px solid #2d5a27;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .event-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d5a27;
            margin-bottom: 15px;
        }
        .event-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .event-detail {
            margin: 5px 0;
        }
        .event-detail strong {
            color: #2d5a27;
        }
        .certificate-code {
            background: #e8f5e8;
            border: 2px dashed #2d5a27;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .code-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .code-value {
            font-size: 18px;
            font-weight: bold;
            color: #2d5a27;
            font-family: monospace;
        }
        .cta-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            background-color: #2d5a27;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #2d5a27;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌱</div>
            <div class="title">EcoEvents</div>
            <div class="subtitle">Votre certificat est prêt !</div>
        </div>

        <div class="content">
            <div class="greeting">Bonjour {{ $reservation->user->name }},</div>
            
            <div class="message">
                Excellente nouvelle ! Votre certificat de participation est maintenant disponible. 
                Nous sommes ravis de vous confirmer que vous avez obtenu votre certification pour votre participation à notre événement.
            </div>

            <div class="certificate-info">
                <div class="event-title">{{ $reservation->event->title }}</div>
                <div class="event-details">
                    <div class="event-detail">
                        <strong>Date :</strong> {{ $reservation->event->date->format('d/m/Y') }}
                    </div>
                    <div class="event-detail">
                        <strong>Heure :</strong> {{ $reservation->event->date->format('H:i') }}
                    </div>
                    <div class="event-detail">
                        <strong>Lieu :</strong> {{ $reservation->event->location }}
                    </div>
                    <div class="event-detail">
                        <strong>Points gagnés :</strong> {{ $certification->points_earned }}
                    </div>
                </div>
            </div>

            <div class="certificate-code">
                <div class="code-label">Code de vérification de votre certificat :</div>
                <div class="code-value">{{ $certification->certificate_code }}</div>
            </div>

            <div class="cta-buttons">
                <a href="{{ route('user.certificates.view', $certification->certificate_code) }}" class="btn btn-primary">
                    📄 Voir mon certificat
                </a>
                <a href="{{ route('user.certificates.download', $certification->certificate_code) }}" class="btn btn-secondary">
                    💾 Télécharger PDF
                </a>
            </div>

            <div class="message">
                <strong>Félicitations !</strong> Votre engagement en faveur de l'environnement est reconnu et valorisé. 
                Ce certificat témoigne de votre participation active à nos initiatives écologiques.
            </div>
        </div>

        <div class="footer">
            <div class="social-links">
                <a href="#">🌐 Site web</a>
                <a href="#">📧 Contact</a>
                <a href="#">📱 Réseaux sociaux</a>
            </div>
            <p>
                Cet email a été envoyé automatiquement par EcoEvents.<br>
                Si vous avez des questions, n'hésitez pas à nous contacter.
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                EcoEvents - Ensemble pour un avenir plus vert 🌱
            </p>
        </div>
    </div>
</body>
</html>

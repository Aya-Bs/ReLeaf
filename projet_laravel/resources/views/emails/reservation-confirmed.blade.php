<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de réservation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2d5a27, #4a7c59);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .confirmation-box {
            background: #e8f5e8;
            border-left: 4px solid #2d5a27;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 8px;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #2d5a27;
        }
        .btn {
            display: inline-block;
            background: #2d5a27;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🎉</div>
            <h1>Réservation Confirmée !</h1>
            <p>Votre place est maintenant garantie</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            
            <div class="confirmation-box">
                <h3 style="margin-top: 0; color: #2d5a27;">✅ Excellente nouvelle !</h3>
                <p>Votre réservation pour l'événement <strong>{{ $eventTitle }}</strong> a été confirmée par notre équipe.</p>
            </div>
            
            <div class="details">
                <h3 style="margin-top: 0; color: #2d5a27;">📋 Détails de votre réservation</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Événement :</span>
                    <span>{{ $eventTitle }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date & Heure :</span>
                    <span>{{ $eventDate->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Votre place :</span>
                    <span><strong>{{ $seatNumber }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Nombre de participants :</span>
                    <span>{{ $numGuests }} personne{{ $numGuests > 1 ? 's' : '' }}</span>
                </div>
                
                @if($hasComments)
                <div class="detail-row">
                    <span class="detail-label">Vos commentaires :</span>
                    <span>{{ $comments }}</span>
                </div>
                @endif
            </div>
            
            <h3 style="color: #2d5a27;">📍 Informations importantes</h3>
            <ul>
                <li><strong>Arrivée :</strong> Présentez-vous 15 minutes avant le début de l'événement</li>
                <li><strong>Pièce d'identité :</strong> Apportez une pièce d'identité valide</li>
                <li><strong>Contact :</strong> Conservez cet email comme justificatif de réservation</li>
                @if($certification)
                <li><strong>Certificat :</strong> Un certificat de participation vous sera remis à la fin de l'événement</li>
                @endif
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/events" class="btn">Voir tous nos événements</a>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>💡 Conseil :</strong> Ajoutez cet événement à votre calendrier pour ne pas l'oublier !
            </div>
        </div>
        
        <div class="footer">
            <p><strong>EcoEvents</strong> - Ensemble pour un avenir durable</p>
            <p>
                <a href="{{ config('app.url') }}" style="color: #2d5a27;">Visitez notre site</a> |
                <a href="mailto:{{ config('mail.from.address') }}" style="color: #2d5a27;">Nous contacter</a>
            </p>
            <p style="font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>

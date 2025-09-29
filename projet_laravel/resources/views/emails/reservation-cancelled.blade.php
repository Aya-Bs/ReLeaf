<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Annulation de réservation</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
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
        .cancellation-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
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
            color: #dc3545;
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
            <div class="icon">❌</div>
            <h1>Réservation Annulée</h1>
            <p>Votre réservation a été annulée</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            
            <div class="cancellation-box">
                <h3 style="margin-top: 0; color: #dc3545;">📋 Annulation de réservation</h3>
                <p>Nous sommes désolés de vous informer que votre réservation pour l'événement <strong>{{ $eventTitle }}</strong> a été annulée.</p>
            </div>
            
            <div class="details">
                <h3 style="margin-top: 0; color: #dc3545;">🔍 Détails de la réservation annulée</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Événement :</span>
                    <span>{{ $eventTitle }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date & Heure :</span>
                    <span>{{ $eventDate->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Place réservée :</span>
                    <span>{{ $seatNumber }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Motif :</span>
                    <span>{{ $reason }}</span>
                </div>
            </div>
            
            <h3 style="color: #2d5a27;">🌱 Ne vous découragez pas !</h3>
            <p>Cette annulation peut être due à diverses raisons (événement complet, problème technique, etc.). Nous vous encourageons à :</p>
            <ul>
                <li>Consulter nos autres événements disponibles</li>
                <li>Vous inscrire à notre newsletter pour être informé des prochains événements</li>
                <li>Nous contacter si vous avez des questions</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/events" class="btn">Découvrir d'autres événements</a>
            </div>
            
            <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>💚 Merci pour votre engagement :</strong> Votre intérêt pour les causes environnementales nous touche. Continuez à faire la différence !
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

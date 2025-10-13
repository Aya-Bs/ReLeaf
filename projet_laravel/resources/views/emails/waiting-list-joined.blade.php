<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste d'attente - EcoEvents</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2d5a27, #3d6b35);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .event-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #2d5a27;
        }
        .event-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d5a27;
            margin-bottom: 10px;
        }
        .event-details {
            color: #666;
            font-size: 14px;
        }
        .position-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .position-number {
            font-size: 24px;
            font-weight: bold;
            color: #856404;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2d5a27;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #234420;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 EcoEvents - Liste d'attente</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $waitingList->user_name }} !</h2>
            
            <p>Vous avez été ajouté avec succès à la liste d'attente pour l'événement suivant :</p>
            
            <div class="event-card">
                <div class="event-title">{{ $waitingList->event->title }}</div>
                <div class="event-details">
                    <p><strong>📅 Date :</strong> {{ $waitingList->event->date->format('d/m/Y à H:i') }}</p>
                    <p><strong>📍 Lieu :</strong> {{ $waitingList->event->location }}</p>
                    <p><strong>👥 Organisateur :</strong> {{ $waitingList->event->user->name }}</p>
                </div>
            </div>
            
            <div class="position-info">
                <div class="position-number">Position #{{ $waitingList->position }}</div>
                <p>Vous êtes en position <strong>{{ $waitingList->position }}</strong> dans la liste d'attente.</p>
            </div>
            
            <div class="info-box">
                <h3>📋 Que se passe-t-il maintenant ?</h3>
                <ul>
                    <li>✅ Vous êtes sur la liste d'attente et serez notifié si une place se libère</li>
                    <li>📧 Vous recevrez un email de confirmation si vous êtes promu</li>
                    <li>⏰ Les promotions se font automatiquement en cas d'annulation</li>
                    <li>🎫 Si promu, votre réservation sera automatiquement confirmée</li>
                </ul>
            </div>
            
            <p>Merci de votre patience et à bientôt sur EcoEvents !</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('home') }}" class="btn">Retourner sur EcoEvents</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le système EcoEvents.</p>
            <p>Pour toute question, contactez-nous à l'adresse support@ecoevents.tn</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotion de la liste d'attente - EcoEvents</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
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
        .success-banner {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .event-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .event-title {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 10px;
        }
        .event-details {
            color: #666;
            font-size: 14px;
        }
        .reservation-details {
            background-color: #e8f5e8;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .seat-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 10px 0;
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
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Félicitations !</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $waitingList->user_name }} !</h2>
            
            <div class="success-banner">
                <div class="success-icon">🎫</div>
                <h3>Vous avez été promu de la liste d'attente !</h3>
                <p>Une place s'est libérée et vous avez été automatiquement sélectionné.</p>
            </div>
            
            <p>Votre réservation a été <strong>automatiquement confirmée</strong> pour l'événement suivant :</p>
            
            <div class="event-card">
                <div class="event-title">{{ $waitingList->event->title }}</div>
                <div class="event-details">
                    <p><strong>📅 Date :</strong> {{ $waitingList->event->date->format('d/m/Y à H:i') }}</p>
                    <p><strong>📍 Lieu :</strong> {{ $waitingList->event->location }}</p>
                    <p><strong>👥 Organisateur :</strong> {{ $waitingList->event->user->name }}</p>
                </div>
            </div>
            
            <div class="reservation-details">
                <h3>🎫 Détails de votre réservation</h3>
                <p><strong>Statut :</strong> <span style="color: #28a745; font-weight: bold;">✅ CONFIRMÉE</span></p>
                <div class="seat-number">Place {{ $reservation->seat_number }}</div>
                <p><strong>Nombre d'invités :</strong> {{ $reservation->num_guests }}</p>
                <p><strong>Réservé le :</strong> {{ $reservation->reserved_at->format('d/m/Y à H:i') }}</p>
            </div>
            
            <div class="info-box">
                <h3>📋 Prochaines étapes</h3>
                <ul>
                    <li>✅ Votre réservation est confirmée et garantie</li>
                    <li>📅 Notez la date de l'événement : <strong>{{ $waitingList->event->date->format('d/m/Y à H:i') }}</strong></li>
                    <li>📍 Rendez-vous à : <strong>{{ $waitingList->event->location }}</strong></li>
                    <li>🎫 Votre place {{ $reservation->seat_number }} vous attend !</li>
                </ul>
            </div>
            
            <p>Félicitations et merci d'avoir choisi EcoEvents !</p>
            
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

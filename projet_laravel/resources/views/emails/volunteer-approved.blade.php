<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volontaire Approuvé - ReLeaf</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .success-icon {
            font-size: 48px;
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌱 ReLeaf</h1>
        <h2>Félicitations !</h2>
    </div>
    
    <div class="content">
        <div class="success-icon">✅</div>
        
        <h3>Bonjour {{ $volunteer->user->name }},</h3>
        
        <p>Nous avons le plaisir de vous informer que votre candidature pour devenir volontaire sur notre plateforme <strong>ReLeaf</strong> a été <strong>approuvée</strong> !</p>
        
        <p>Votre profil de volontaire est maintenant actif et vous pouvez :</p>
        
        <ul>
            <li>✅ Consulter les missions disponibles</li>
            <li>✅ Postuler aux événements et campagnes</li>
            <li>✅ Accéder à votre tableau de bord personnel</li>
            <li>✅ Participer à notre communauté de volontaires</li>
        </ul>
        
        <p><strong>Prochaines étapes :</strong></p>
        <p>Connectez-vous à votre compte pour découvrir les opportunités de bénévolat qui vous correspondent et commencer à faire la différence dans votre communauté !</p>
        
        <div style="text-align: center;">
            <a href="{{ route('volunteers.show', $volunteer) }}" class="btn">
                Accéder à mon profil volontaire
            </a>
        </div>
        
        <p>Merci pour votre engagement envers notre mission environnementale. Ensemble, nous pouvons créer un impact positif pour notre planète !</p>
        
        <p><strong>L'équipe ReLeaf</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        <p>© {{ date('Y') }} ReLeaf. Tous droits réservés.</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation de mot de passe - EcoEvents</title>
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
            background-color: #2d5a27;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            margin: 20px 0;
            background-color: #e9ecef;
            border-radius: 5px;
            letter-spacing: 3px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2d5a27;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>EcoEvents</h1>
    </div>

    <div class="content">
        <h2>Réinitialisation de mot de passe</h2>
        
        <p>Bonjour,</p>
        
        <p>Vous avez demandé la réinitialisation de votre mot de passe sur EcoEvents. Voici votre code de vérification :</p>
        
        <div class="code">
            {{ $code }}
        </div>

        <p>Vous pouvez également cliquer sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">Réinitialiser mon mot de passe</a>
        </div>

        <p><strong>Important :</strong></p>
        <ul>
            <li>Ce code est valable pendant 60 minutes</li>
            <li>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email</li>
            <li>Ne partagez jamais ce code avec quelqu'un</li>
        </ul>

        <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
        <p style="word-break: break-all;">{{ $url }}</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement par EcoEvents. Merci de ne pas y répondre.</p>
        <p>&copy; {{ date('Y') }} EcoEvents. Tous droits réservés.</p>
    </div>
</body>
</html>




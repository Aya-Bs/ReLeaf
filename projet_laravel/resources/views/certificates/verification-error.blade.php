<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Vérification - ReLeaf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        .error-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .error-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .error-content {
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="error-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="error-card">
                        <!-- Header -->
                        <div class="error-header">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h2 class="mb-0">Erreur de Vérification</h2>
                        </div>

                        <!-- Content -->
                        <div class="error-content">
                            <p class="text-muted mb-4">{{ $message }}</p>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Code d'erreur:</strong> {{ $error_code }}
                            </div>

                            <p class="small text-muted mb-4">
                                Vérifiez que le lien de vérification est correct et complet.
                                Si le problème persiste, contactez l'administrateur.
                            </p>

                            <a href="{{ route('home') }}" class="btn btn-outline-danger">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

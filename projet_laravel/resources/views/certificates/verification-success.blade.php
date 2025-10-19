<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Certificat - ReLeaf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .verification-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .certificate-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .qr-code-container {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
        }
        .certificate-details {
            padding: 2rem;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <div class="verification-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="certificate-card">
                        <!-- Header -->
                        <div class="certificate-header">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h2 class="mb-0">Certificat Vérifié</h2>
                            <p class="mb-0 mt-2">Ce certificat est authentique et valide</p>
                        </div>

                        <!-- QR Code -->
                        <div class="qr-code-container">
                            @if($certificate->qr_code_url)
                                <img src="{{ $certificate->qr_code_url }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                <p class="mt-3 text-muted">
                                    <i class="fas fa-qrcode me-2"></i>
                                    Code QR de vérification
                                </p>
                            @endif
                        </div>

                        <!-- Certificate Details -->
                        <div class="certificate-details">
                            <h4 class="mb-4">
                                <i class="fas fa-certificate me-2 text-success"></i>
                                Détails du Certificat
                            </h4>

                            <div class="detail-row">
                                <span class="detail-label">Code Certificat</span>
                                <span class="detail-value font-monospace">{{ $certificate->certificate_code }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Type</span>
                                <span class="detail-value text-capitalize">{{ $certificate->type }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Points Obtenus</span>
                                <span class="detail-value">
                                    <span class="badge bg-success">{{ $certificate->points_earned }} pts</span>
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Date d'Émission</span>
                                <span class="detail-value">{{ $certificate->date_awarded->format('d/m/Y à H:i') }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Événement</span>
                                <span class="detail-value">{{ $certificate->reservation->event->title }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Date Événement</span>
                                <span class="detail-value">{{ $certificate->reservation->event->date->format('d/m/Y à H:i') }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Lieu</span>
                                <span class="detail-value">{{ $certificate->reservation->event->location->name ?? 'Non défini' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Participant</span>
                                <span class="detail-value">{{ $certificate->reservation->user->name }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Émis par</span>
                                <span class="detail-value">{{ $certificate->issuedBy->name ?? 'Administrateur' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Statut</span>
                                <span class="detail-value">
                                    <span class="badge bg-success status-badge">
                                        <i class="fas fa-check me-1"></i>Valide
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center p-3 bg-light">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Certificat vérifié par ReLeaf - {{ now()->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-light">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

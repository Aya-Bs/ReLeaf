<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Afficher les certifications de l'utilisateur
     */
    public function index()
    {
        $certifications = Certification::with(['reservation.event.location', 'issuedBy'])
            ->whereHas('reservation', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('date_awarded', 'desc')
            ->get();

        return view('certificates.index', compact('certifications'));
    }

    /**
     * Afficher une certification spécifique
     */
    public function show(string $code)
    {
        $certification = Certification::with(['reservation.event.location', 'reservation.user', 'issuedBy'])
            ->where('certificate_code', $code)
            ->whereHas('reservation', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        return view('certificates.show', compact('certification'));
    }

    /**
     * Télécharger le PDF du certificat
     */
    public function download(string $code)
    {
        $certification = Certification::with(['reservation.event.location', 'reservation.user', 'issuedBy'])
            ->where('certificate_code', $code)
            ->whereHas('reservation', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        // Générer le PDF avec options pour forcer la régénération
        $pdf = Pdf::loadView('certificates.pdf', compact('certification'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'helvetica',
                'defaultMediaType' => 'print',
                'dpi' => 150,
                'enable_font_subsetting' => false,
                'isPhpEnabled' => false,
                'isJavascriptEnabled' => false,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
                'fontHeightRatio' => 1.1,
                'enable_html5_parser' => true,
                'chroot' => realpath(base_path()),
                'logOutputFile' => null,
                'tempDir' => sys_get_temp_dir(),
                'fontDir' => storage_path('fonts'),
                'fontCache' => storage_path('fonts'),
                'defaultPaperSize' => 'a4',
                'defaultPaperOrientation' => 'portrait',
                'pdfBackend' => 'CPDF',
                'showWarnings' => false,
                'convertEntities' => true,
                'allowedProtocols' => [
                    'data://' => ['rules' => []],
                    'file://' => ['rules' => []],
                    'http://' => ['rules' => []],
                    'https://' => ['rules' => []],
                ],
                'allowedRemoteHosts' => null,
                'artifactPathValidation' => null,
            ]);

        $filename = 'Certificat_'.$certification->certificate_code.'_'.$certification->reservation->user->name.'.pdf';
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);

        return $pdf->download($filename);
    }

    /**
     * Afficher le PDF dans le navigateur
     */
    public function view(string $code)
    {
        $certification = Certification::with(['reservation.event.location', 'reservation.user', 'issuedBy'])
            ->where('certificate_code', $code)
            ->whereHas('reservation', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        // Générer le PDF avec options pour forcer la régénération
        $pdf = Pdf::loadView('certificates.pdf', compact('certification'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'helvetica',
                'defaultMediaType' => 'print',
                'dpi' => 150,
                'enable_font_subsetting' => false,
                'isPhpEnabled' => false,
                'isJavascriptEnabled' => false,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
                'fontHeightRatio' => 1.1,
                'enable_html5_parser' => true,
                'chroot' => realpath(base_path()),
                'logOutputFile' => null,
                'tempDir' => sys_get_temp_dir(),
                'fontDir' => storage_path('fonts'),
                'fontCache' => storage_path('fonts'),
                'defaultPaperSize' => 'a4',
                'defaultPaperOrientation' => 'portrait',
                'pdfBackend' => 'CPDF',
                'showWarnings' => false,
                'convertEntities' => true,
                'allowedProtocols' => [
                    'data://' => ['rules' => []],
                    'file://' => ['rules' => []],
                    'http://' => ['rules' => []],
                    'https://' => ['rules' => []],
                ],
                'allowedRemoteHosts' => null,
                'artifactPathValidation' => null,
            ]);

        return $pdf->stream('Certificat_'.$certification->certificate_code.'.pdf');
    }

    /**
     * Vérifier un certificat par code (accès public)
     */
    public function verify(Request $request, ?string $code = null)
    {
        // Si un code est fourni dans l'URL, l'utiliser directement
        $searchCode = $code ?? $request->get('code');

        if (! $searchCode) {
            // Aucun code fourni, afficher le formulaire de recherche
            return view('certificates.verify', [
                'certification' => null,
                'message' => null,
            ]);
        }

        $certification = Certification::with(['reservation.event.location', 'reservation.user', 'issuedBy'])
            ->where('certificate_code', $searchCode)
            ->first();

        if (! $certification) {
            return view('certificates.verify', [
                'certification' => null,
                'message' => 'Certificat non trouvé ou code invalide.',
            ]);
        }

        return view('certificates.verify', [
            'certification' => $certification,
            'message' => null,
        ]);
    }

    /**
     * Dashboard admin - Gestion des certifications
     */
    public function adminIndex(Request $request)
    {
        // Récupérer les réservations confirmées sans certificat
        $pendingReservations = \App\Models\Reservation::confirmed()
            ->whereDoesntHave('certification')
            ->with(['user', 'event'])
            ->orderBy('confirmed_at', 'desc')
            ->get();

        // Récupérer les certifications existantes
        $query = Certification::with(['reservation.event', 'reservation.user', 'issuedBy'])
            ->orderBy('date_awarded', 'desc');

        // Filtres
        if ($request->filled('event_id')) {
            $query->whereHas('reservation.event', function ($q) use ($request) {
                $q->where('id', $request->event_id);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->whereHas('reservation', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $certifications = $query->paginate(15);
        $events = \App\Models\Event::published()->get();
        $users = \App\Models\User::where('role', 'user')->get();

        return view('admin.certificates.index', compact('certifications', 'pendingReservations', 'events', 'users'));
    }

    /**
     * Accorder un certificat à une réservation confirmée
     */
    public function grantCertificate(\App\Models\Reservation $reservation)
    {
        // Vérifier que la réservation est confirmée et n'a pas déjà de certificat
        if (! $reservation->isConfirmed() || $reservation->certification) {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas recevoir de certificat.');
        }

        // Générer le certificat
        $certification = Certification::generateForReservation($reservation, auth()->user());

        // Envoyer l'email de notification avec le certificat
        \Illuminate\Support\Facades\Mail::to($reservation->user->email)
            ->send(new \App\Mail\CertificateGranted($reservation, $certification));

        return redirect()->back()->with('success', 'Certificat accordé avec succès et email envoyé.');
    }
}

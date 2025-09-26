<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorAuthController extends Controller
{
    /**
     * Afficher la page de configuration 2FA
     */
    public function show()
    {
        $user = auth()->user();
        
        if (!$user->two_factor_secret) {
            $google2fa = new Google2FA();
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $this->generateQrCodeUrl($user);
        $qrCodeSvg = $this->generateQrCodeSvg($qrCodeUrl);

        return view('auth.2fa.setup', [
            'qrCodeSvg' => $qrCodeSvg,
            'secret' => $user->two_factor_secret,
            'enabled' => $user->two_factor_enabled
        ]);
    }

    /**
     * Activer 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'current_password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe est incorrect.']);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Le code est invalide.']);
        }

        $user->two_factor_enabled = true;
        $user->two_factor_recovery_codes = $this->generateRecoveryCodes();
        $user->save();

        return redirect()->route('profile.security')
            ->with('success', 'L\'authentification à deux facteurs a été activée.');
    }

    /**
     * Désactiver 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe est incorrect.']);
        }

        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return redirect()->route('profile.security')
            ->with('success', 'L\'authentification à deux facteurs a été désactivée.');
    }

    /**
     * Vérifier le code 2FA lors de la connexion
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'Le code est invalide.']);
    }

    /**
     * Générer l'URL pour le QR code
     */
    private function generateQrCodeUrl($user)
    {
        $google2fa = new Google2FA();
        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
    }

    /**
     * Générer le QR code en SVG
     */
    private function generateQrCodeSvg($url)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }

    /**
     * Générer les codes de récupération
     */
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = sprintf(
                '%s-%s-%s-%s',
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4)
            );
        }
        return json_encode($codes);
    }
}

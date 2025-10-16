<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'Votre email est déjà vérifié. Vous pouvez vous connecter.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Déconnecter l'utilisateur et rediriger vers login
        auth()->logout();

        return redirect()->route('login')
            ->with('success', 'Email vérifié avec succès ! Vous pouvez maintenant vous connecter.');
    }
}

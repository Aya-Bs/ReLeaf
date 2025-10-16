<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset code to user's email.
     */
    public function sendResetCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
        ]);

        $email = $request->email;

        // Create reset token
        $resetToken = PasswordResetToken::createToken($email);

        // Send email with reset code
        $this->sendResetCodeEmail($email, $resetToken->token);

        return redirect()->back()->with('success', 'Un code de vérification a été envoyé à votre adresse email.');
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(Request $request): View
    {
        $token = $request->query('token');
        $email = $request->query('email');

        // Verify token exists and is valid
        $resetToken = PasswordResetToken::findValidToken($email, $token);

        if (! $resetToken) {
            abort(403, 'Code de vérification invalide ou expiré.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
            'token.required' => 'Le code de vérification est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
        ]);

        // Verify token exists and is valid
        $resetToken = PasswordResetToken::findValidToken($request->email, $request->token);

        if (! $resetToken) {
            return redirect()->back()->withErrors(['token' => 'Code de vérification invalide ou expiré.']);
        }

        // Update user's password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the reset token
        PasswordResetToken::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été modifié avec succès. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Send reset code email.
     */
    private function sendResetCodeEmail(string $email, string $token): void
    {
        // Extract first 6 characters of token for display
        $displayCode = substr($token, 0, 6);

        $data = [
            'code' => $displayCode,
            'token' => $token,
            'email' => $email,
            'url' => route('password.reset', ['token' => $token, 'email' => $email]),
        ];

        // Envoyer l'email avec le code de réinitialisation
        try {
            Mail::send('emails.password-reset', $data, function ($message) use ($email) {
                $message->to($email)
                    ->subject('Code de vérification - Réinitialisation de mot de passe');
            });

            // Log pour le développement
            \Log::info('Code de réinitialisation envoyé à '.$email.' : '.$displayCode);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du code de réinitialisation : '.$e->getMessage());
            throw new \Exception('Impossible d\'envoyer le code de réinitialisation. Veuillez réessayer plus tard.');
        }
    }
}

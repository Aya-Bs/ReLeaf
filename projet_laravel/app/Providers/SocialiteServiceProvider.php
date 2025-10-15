<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Rien à faire ici car Socialite est déjà enregistré par son propre provider
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configuration de Google OAuth
        $googleConfig = config('services.google');
        
        if ($googleConfig) {
            config([
                'services.google' => [
                    'client_id' => $googleConfig['client_id'],
                    'client_secret' => $googleConfig['client_secret'],
                    'redirect' => $googleConfig['redirect'],
                ]
            ]);
        }
    }
}
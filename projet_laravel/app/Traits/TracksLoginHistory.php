<?php

namespace App\Traits;

use App\Models\LoginHistory;
use App\Services\LocationService;
use Illuminate\Http\Request;

trait TracksLoginHistory
{
    protected function logSuccessfulLogin(Request $request)
    {
        $locationService = new LocationService;
        $location = $locationService->getLocation($request->ip());

        $this->loginHistory()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location ? $location['city'].', '.$location['country'] : null,
            'is_suspicious' => $this->isSuspiciousLogin($request),
        ]);

        if ($this->isSuspiciousLogin($request)) {
            $this->notifySuspiciousLogin($request);
        }
    }

    protected function isSuspiciousLogin(Request $request): bool
    {
        $lastLogin = $this->loginHistory()
            ->latest()
            ->first();

        if (! $lastLogin) {
            return false;
        }

        // Vérifier si la connexion vient d'un pays différent
        $locationService = new LocationService;
        $currentLocation = $locationService->getLocation($request->ip());
        $lastLocation = $locationService->getLocation($lastLogin->ip_address);

        if ($currentLocation && $lastLocation &&
            $currentLocation['countryCode'] !== $lastLocation['countryCode']) {
            return true;
        }

        // Vérifier si l'agent utilisateur est différent
        if ($request->userAgent() !== $lastLogin->user_agent) {
            return true;
        }

        return false;
    }

    protected function notifySuspiciousLogin(Request $request)
    {
        // Envoyer une notification par email
        $this->notify(new \App\Notifications\SuspiciousLoginAttempt([
            'ip_address' => $request->ip(),
            'location' => $locationService->getLocation($request->ip())['city'] ?? 'Unknown',
            'user_agent' => $request->userAgent(),
            'time' => now()->format('Y-m-d H:i:s'),
        ]));
    }

    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class);
    }
}

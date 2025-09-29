<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Log;

class EnsureSponsorProfile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->role === 'sponsor' && !$user->sponsor) {
            try {
                $s = Sponsor::create([
                    'user_id' => $user->id,
                    'company_name' => $user->name ?: ('Sponsor ' . $user->id),
                    'contact_email' => $user->email,
                    'motivation' => 'Profil sponsor à compléter.',
                    'sponsorship_type' => 'argent',
                    'status' => 'pending'
                ]);
                Log::info('EnsureSponsorProfile middleware created placeholder sponsor', [
                    'user_id' => $user->id,
                    'sponsor_id' => $s->id
                ]);
            } catch (\Throwable $e) {
                Log::error('EnsureSponsorProfile failed to create sponsor', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $next($request);
    }
}

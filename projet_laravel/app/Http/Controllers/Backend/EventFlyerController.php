<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateEventFlyer;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventFlyerController extends Controller
{
    public function generate(Request $request, Event $event)
    {
        $isAdmin = \Illuminate\Support\Facades\Auth::check() && (\Illuminate\Support\Facades\Auth::user()->role === 'admin');
        if ($event->user_id !== Auth::id() && !$isAdmin) {
            return back()->with('error', 'Accès non autorisé.');
        }

        // Only once: if flyer already generated or paths exist, block
        if (!empty($event->flyer_generated_at) || !empty($event->flyer_path) || !empty($event->flyer_image_path)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'already-generated',
                    'flyer_image_path' => $event->flyer_image_path,
                    'flyer_path' => $event->flyer_path,
                ]);
            }
            return back()->with('error', 'Le flyer a déjà été généré pour cet événement.');
        }

        // Run synchronously for deterministic UX
        try {
            $event->update(['flyer_status' => 'running']);
            $res = app(\App\Services\FlyerGenerator::class)->generate($event);
            $event->update([
                'flyer_image_path' => $res['bg_path'] ?? null,
                'flyer_path' => $res['pdf_path'] ?? null,
                'flyer_generated_at' => now(),
                'flyer_status' => 'success',
                'flyer_prompt' => $res['prompt'] ?? null,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'flyer_image_path' => $event->flyer_image_path,
                    'flyer_path' => $event->flyer_path,
                ]);
            }
            return back()->with('success', 'Flyer généré.');
        } catch (\Throwable $e) {
            $event->update(['flyer_status' => 'failed']);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Erreur lors de la génération: ' . $e->getMessage());
        }
    }

    public function downloadImage(Event $event)
    {
        $isAdmin = \Illuminate\Support\Facades\Auth::check() && (\Illuminate\Support\Facades\Auth::user()->role === 'admin');
        if ($event->user_id !== Auth::id() && !$isAdmin) {
            return back()->with('error', 'Accès non autorisé.');
        }
        if (empty($event->flyer_image_path)) {
            return back()->with('error', 'Aucune image de flyer disponible.');
        }
        $abs = public_path($event->flyer_image_path);
        if (!file_exists($abs)) {
            return back()->with('error', 'Fichier introuvable.');
        }
        return response()->download($abs, 'flyer-event-' . $event->id . '.png');
    }

    public function downloadPdf(Event $event)
    {
        $isAdmin = \Illuminate\Support\Facades\Auth::check() && (\Illuminate\Support\Facades\Auth::user()->role === 'admin');
        if ($event->user_id !== Auth::id() && !$isAdmin) {
            return back()->with('error', 'Accès non autorisé.');
        }
        if (empty($event->flyer_path)) {
            return back()->with('error', 'Aucun PDF de flyer disponible.');
        }
        $abs = public_path($event->flyer_path);
        if (!file_exists($abs)) {
            return back()->with('error', 'Fichier introuvable.');
        }
        return response()->download($abs, 'flyer-event-' . $event->id . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SocialShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialShareController extends Controller
{
    /**
     * Show social share interface for organizers
     */
    public function create(Event $event)
    {
        // Authorization - only event organizer or admin
        if ($event->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $shareStats = $this->getShareStatistics($event);

        return view('frontend.events.social-share', compact('event', 'shareStats'));
    }

    /**
     * Handle Facebook share
     */
    public function store(Request $request, Event $event)
    {
        \Log::info('Facebook Share Request Started', [
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'platform' => $request->platform,
            'ip' => $request->ip()
        ]);

        // Authorization
        if ($event->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            \Log::warning('Unauthorized share attempt', [
                'event_id' => $event->id,
                'user_id' => auth()->id()
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'platform' => 'required|in:facebook,whatsapp'
        ]);

        $platform = $request->platform;

        try {
            \Log::info('Processing share for platform: ' . $platform);

            if ($platform === 'facebook') {
                $facebookUrl = $this->generateFacebookShareUrl($event);
                
                \Log::info('Facebook URL generated', ['url' => $facebookUrl]);

                // Record the share attempt
                $socialShare = SocialShare::create([
                    'event_id' => $event->id,
                    'user_id' => auth()->id(),
                    'platform' => 'facebook',
                    'share_url' => $facebookUrl,
                    'share_data' => ['method' => 'direct_share'],
                    'shared_at' => now(),
                ]);

                \Log::info('Social share record created', ['share_id' => $socialShare->id]);

                return response()->json([
                    'success' => true,
                    'type' => 'direct',
                    'url' => $facebookUrl,
                    'message' => 'Partage Facebook ouvert dans une nouvelle fenêtre!',
                    'debug' => [
                        'event_id' => $event->id,
                        'share_id' => $socialShare->id,
                        'timestamp' => now()->toISOString()
                    ]
                ]);
            }

            if ($platform === 'whatsapp') {
                $whatsappUrl = $this->generateWhatsappUrl($event);
                \Log::info('WhatsApp URL generated', ['url' => $whatsappUrl]);
                
                return response()->json([
                    'success' => true,
                    'type' => 'direct',
                    'url' => $whatsappUrl,
                    'debug' => ['timestamp' => now()->toISOString()]
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Share processing failed', [
                'error' => $e->getMessage(),
                'platform' => $platform,
                'event_id' => $event->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur: ' . $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Generate Facebook Share URL
     */
    protected function generateFacebookShareUrl(Event $event): string
    {
        $eventUrl = route('events.show', $event);
        $message = $this->generateFacebookMessage($event);
        
        // Facebook share dialog URL
        $shareUrl = "https://www.facebook.com/sharer/sharer.php?" . http_build_query([
            'u' => $eventUrl,
            'quote' => $message,
            'hashtag' => '#EcoEvents'
        ]);

        \Log::debug('Facebook share URL constructed', [
            'event_url' => $eventUrl,
            'message_length' => strlen($message),
            'final_url_length' => strlen($shareUrl)
        ]);

        return $shareUrl;
    }

    /**
     * Generate Facebook message
     */
    protected function generateFacebookMessage(Event $event): string
    {
        $date = $event->date->format('d/m/Y à H:i');
        $location = $event->location->name ?? 'Lieu à confirmer';
        
        return "🎉 NOUVEL ÉVÉNEMENT ÉCOLOGIQUE ! 🎉

{$event->title}

📅 Quand : {$date}
📍 Où : {$location}

{$event->description}

Rejoignez-nous pour cet événement engagé pour l'environnement ! 🌱

#Écologie #Événement #DéveloppementDurable";
    }

    /**
     * Generate WhatsApp share URL
     */
    protected function generateWhatsappUrl(Event $event): string
    {
        $text = "🎉 Rejoins-moi à cet événement écologique!\n\n";
        $text .= "{$event->title}\n";
        $text .= "📅 " . $event->date->format('d/m/Y à H:i') . "\n";
        $text .= "📍 " . ($event->location->name ?? 'Lieu à confirmer') . "\n\n";
        $text .= "Plus d'infos: " . route('events.show', $event);
        
        return "https://wa.me/?text=" . urlencode($text);
    }

    /**
     * Get share statistics for event
     */
    protected function getShareStatistics(Event $event)
    {
        return [
            'total_shares' => $event->socialShares()->count(),
            'platform_breakdown' => $event->socialShares()
                ->selectRaw('platform, COUNT(*) as count')
                ->groupBy('platform')
                ->get()
                ->pluck('count', 'platform'),
            'recent_shares' => $event->socialShares()
                ->with('user')
                ->latest()
                ->take(5)
                ->get()
        ];
    }

    /**
     * Get share statistics via API
     */
    public function statistics(Event $event)
    {
        // Authorization
        if ($event->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $statistics = $this->getShareStatistics($event);

        return response()->json($statistics);
    }

    /**
     * Test route to check if sharing works
     */
    public function testShare(Event $event)
    {
        \Log::info('Test share endpoint called', ['event_id' => $event->id]);
        
        try {
            $facebookUrl = $this->generateFacebookShareUrl($event);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Share system is working!',
                'facebook_url' => $facebookUrl,
                'event' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'url' => route('events.show', $event)
                ],
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            \Log::error('Test share failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Test failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
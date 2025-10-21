<?php

namespace App\Jobs;

use App\Models\Event;
use App\Services\FlyerGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateEventFlyer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $eventId) {}

    public function handle(FlyerGenerator $gen): void
    {
        $event = Event::find($this->eventId);
        if (!$event) return;

        try {
            Log::info('[FlyerJob] Start generation', ['event_id' => $event->id, 'title' => $event->title]);
            $res = $gen->generate($event);
            Log::info('[FlyerJob] Generation success', ['bg' => $res['bg_path'] ?? null, 'pdf' => $res['pdf_path'] ?? null]);
            $event->update([
                'flyer_image_path' => $res['bg_path'] ?? null,
                'flyer_path' => $res['pdf_path'] ?? null,
                'flyer_generated_at' => now(),
                'flyer_status' => 'success',
                'flyer_prompt' => $res['prompt'] ?? null,
            ]);
            Log::info('[FlyerJob] Event updated', ['event_id' => $event->id]);
        } catch (\Throwable $e) {
            Log::error('[FlyerJob] Generation failed', ['event_id' => $this->eventId, 'error' => $e->getMessage()]);
            $event->update([
                'flyer_status' => 'failed',
                'flyer_prompt' => ($event->flyer_prompt ? $event->flyer_prompt . "\n" : '') . 'ERR: ' . $e->getMessage(),
            ]);
            throw $e; // let queue retry if configured
        }
    }
}

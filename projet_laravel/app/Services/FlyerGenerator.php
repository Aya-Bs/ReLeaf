<?php

namespace App\Services;

use App\Models\Event;
use App\Services\Ai\GeminiTextProvider;
use App\Services\Ai\CloudflareImageProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FlyerGenerator
{
    public function __construct(
        protected GeminiTextProvider $text,
        protected CloudflareImageProvider $image
    ) {}

    public function generate(Event $event): array
    {
        $disk = config('flyer.disk', 'public');
        $base = trim(config('flyer.base_path', 'flyers'), '/');
        $toPublic = (bool) config('flyer.save_to_public', true);
        $dir = $base . '/' . $event->id;
        $bgRel = $dir . '/flyer-bg.png';
        $pdfRel = $dir . '/flyer.pdf';

        $eventData = [
            'title' => $event->title,
            'theme' => $event->campaign?->name ?? 'Environnement',
            'city' => $event->location?->name ?? '',
            'date' => optional($event->date)->format('Y-m-d H:i') ?? '',
            'description' => $event->description ?? '',
        ];

        // 1) Text (tagline + palette)
        Log::info('[FlyerGen] Requesting Gemini', ['event' => $eventData]);
        $textRes = $this->text->enhance($eventData);
        Log::info('[FlyerGen] Gemini result', ['tagline' => $textRes['tagline'] ?? null, 'colors' => $textRes['colors'] ?? null, 'date_text' => $textRes['date_text'] ?? null, 'quote' => $textRes['quote'] ?? null]);
        $tagline = $textRes['tagline'] ?? '';
        $colors = $textRes['colors'] ?? ['#15A053', '#0E7A3A', '#73C58E'];
        $dateText = $textRes['date_text'] ?? '';
        $quote = $textRes['quote'] ?? '';

        // 2) Build image prompt from event + tagline
        $imgPrompt = "Create an eco-themed poster background. Title: {$eventData['title']}. Theme: {$eventData['theme']}. City: {$eventData['city']}. Date: {$eventData['date']}. ReadableDate: {$dateText}. Tagline: {$tagline}. Quote: {$quote}. Allow clean, minimal typographic text for the ReadableDate and the Quote, balanced and legible. Style: flat/illustrative, soft green gradients, organic shapes, high-contrast center, 1792x1024.";

        // 3) Image generation
        if ($toPublic) {
            @mkdir(public_path($dir), 0775, true);
        } else {
            Storage::disk($disk)->makeDirectory($dir);
        }
        Log::info('[FlyerGen] Calling Cloudflare image', ['model' => config('flyer.image.cloudflare.model'), 'w' => $this->imageWidth($disk), 'h' => $this->imageHeight($disk)]);
        if ($toPublic) {
            $bgAbs = $this->image->generateToAbsolute($imgPrompt, public_path($bgRel));
        } else {
            $bgAbs = $this->image->generate($imgPrompt, $disk, $bgRel);
        }
        Log::info('[FlyerGen] Image saved', ['path' => $bgRel, 'abs' => $bgAbs]);

        // 4) Render Blade and export PDF
        $html = view(config('flyer.template'), [
            'event' => $event,
            'tagline' => $tagline,
            'colors' => $colors,
            'dateText' => $dateText,
            'quote' => $quote,
            'bgRel' => $bgRel,
        ])->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');
        if ($toPublic) {
            file_put_contents(public_path($pdfRel), $pdf->output());
        } else {
            Storage::disk($disk)->put($pdfRel, $pdf->output());
        }
        Log::info('[FlyerGen] PDF written', ['path' => $pdfRel, 'public' => $toPublic]);

        return [
            'bg_path' => $bgRel,
            'pdf_path' => $pdfRel,
            'tagline' => $tagline,
            'colors' => $colors,
            'prompt' => $imgPrompt,
        ];
    }

    private function imageWidth(string $disk): int
    {
        return (int) config('flyer.image.cloudflare.width');
    }
    private function imageHeight(string $disk): int
    {
        return (int) config('flyer.image.cloudflare.height');
    }
}

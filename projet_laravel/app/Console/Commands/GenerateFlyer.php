<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateEventFlyer;

class GenerateFlyer extends Command
{
    protected $signature = 'flyer:generate {event_id} {--sync}';
    protected $description = 'Generate an AI flyer for the given event ID';

    public function handle(): int
    {
        $id = (int) $this->argument('event_id');
        if ($this->option('sync')) {
            // Run inline for live logs
            app(\App\Jobs\GenerateEventFlyer::class, ['eventId' => $id])->handle(app(\App\Services\FlyerGenerator::class));
            $this->info('Flyer generated synchronously. Check storage and logs.');
        } else {
            GenerateEventFlyer::dispatch($id);
            $this->info('Dispatched flyer generation job for event ID: ' . $id);
        }
        return self::SUCCESS;
    }
}

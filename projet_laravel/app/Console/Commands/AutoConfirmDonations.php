<?php

namespace App\Console\Commands;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoConfirmDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:auto-confirm {--hours=24 : Age threshold in hours}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically confirm pending donations older than a configured threshold (default 24h).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = Carbon::now()->subHours($hours);

        // Prefer donated_at if present, otherwise use created_at
        $query = Donation::where('status', 'pending')
            ->where(function ($q) use ($cutoff) {
                $q->whereNotNull('donated_at')->where('donated_at', '<=', $cutoff)
                    ->orWhere(function ($qq) use ($cutoff) {
                        $qq->whereNull('donated_at')->where('created_at', '<=', $cutoff);
                    });
            });

        $count = (clone $query)->count();
        if ($count === 0) {
            $this->info('No pending donations eligible for auto-confirmation.');
            return self::SUCCESS;
        }

        $updated = $query->update(['status' => 'confirmed']);
        $this->info("Auto-confirmed {$updated} donation(s) older than {$hours}h.");
        return self::SUCCESS;
    }
}

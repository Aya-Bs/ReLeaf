<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListAdminsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->info('Aucun administrateur trouvé.');
            return 0;
        }

        $this->info('Liste des administrateurs :');
        $this->line('');

        $headers = ['ID', 'Nom', 'Email', 'Créé le'];
        $rows = [];

        foreach ($admins as $admin) {
            $rows[] = [
                $admin->id,
                $admin->name,
                $admin->email,
                $admin->created_at->format('d/m/Y H:i'),
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}

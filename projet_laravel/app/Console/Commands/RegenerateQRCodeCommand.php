<?php

namespace App\Console\Commands;

use App\Models\Certification;
use Illuminate\Console\Command;

class RegenerateQRCodeCommand extends Command
{
    protected $signature = 'qr:regenerate {--all : Régénérer tous les QR codes}';
    protected $description = 'Régénérer les QR codes pour les certificats existants';

    public function handle()
    {
        $this->info('🔄 Régénération des QR codes...');

        $query = Certification::whereNull('qr_code_path');
        
        if ($this->option('all')) {
            $query = Certification::query();
            $this->warn('⚠️  Régénération de TOUS les QR codes (même ceux existants)');
        }

        $certificates = $query->get();

        if ($certificates->isEmpty()) {
            $this->info('✅ Tous les certificats ont déjà des QR codes !');
            return;
        }

        $this->info("📋 Trouvé {$certificates->count()} certificat(s) à traiter");

        $bar = $this->output->createProgressBar($certificates->count());
        $bar->start();

        foreach ($certificates as $certificate) {
            try {
                // Générer le token de vérification s'il n'existe pas
                if (!$certificate->verification_token) {
                    $certificate->verification_token = $this->generateVerificationToken();
                    $certificate->save();
                }

                // Générer le QR code
                $certificate->generateQRCode();
                
                $this->line("\n✅ Certificat {$certificate->certificate_code} : QR code généré");
                
            } catch (\Exception $e) {
                $this->error("\n❌ Erreur pour le certificat {$certificate->certificate_code}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('🎉 Régénération terminée !');
    }

    private function generateVerificationToken(): string
    {
        do {
            $token = \Illuminate\Support\Str::random(32);
        } while (Certification::where('verification_token', $token)->exists());
        
        return $token;
    }
}
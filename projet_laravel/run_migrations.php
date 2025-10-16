<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Exécution des migrations...\n";

try {
    // Exécuter les migrations
    Artisan::call('migrate', [], $output);

    echo "✅ Migrations exécutées avec succès!\n";
    echo "📋 Résultat:\n";
    echo $output;

    // Vérifier que les tables existent
    echo "\n🔍 Vérification des tables:\n";

    $tables = ['users', 'profiles', 'events', 'event_participations'];
    foreach ($tables as $table) {
        try {
            $count = DB::table('information_schema.tables')
                ->where('table_schema', env('DB_DATABASE', 'laravel'))
                ->where('table_name', $table)
                ->count();

            if ($count > 0) {
                echo "  ✅ Table '$table' existe\n";
            } else {
                echo "  ❌ Table '$table' manquante\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️  Impossible de vérifier la table '$table': ".$e->getMessage()."\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur lors de l'exécution des migrations: ".$e->getMessage()."\n";
    echo "📄 Détails:\n".$e->getTraceAsString()."\n";
}

echo "\n✨ Migration terminée!\n";

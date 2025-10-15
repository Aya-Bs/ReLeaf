<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbots', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // Identifiant de session unique
            $table->unsignedBigInteger('user_id')->nullable(); // Utilisateur connecté (optionnel)
            $table->string('language', 5)->default('fr'); // Langue préférée (fr, en, ar)
            $table->json('conversation_history')->nullable(); // Historique des conversations
            $table->string('last_intent')->nullable(); // Dernière intention détectée
            $table->json('user_preferences')->nullable(); // Préférences utilisateur
            $table->boolean('is_active')->default(true); // Session active
            $table->timestamp('last_activity')->nullable(); // Dernière activité
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};

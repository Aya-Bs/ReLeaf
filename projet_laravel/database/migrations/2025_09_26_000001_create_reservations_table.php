<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Nom de l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('seat_number'); // Numéro de place (ex: A1, B15, etc.)
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('reserved_at')->nullable(); // Moment de la réservation
            $table->timestamp('expires_at')->nullable(); // Expiration du blocage temporaire (5 min)
            $table->timestamp('confirmed_at')->nullable(); // Confirmation admin
            $table->foreignId('confirmed_by')->nullable()->constrained('users'); // Admin qui confirme
            $table->integer('num_guests')->default(1); // Nombre d'invités
            $table->text('comments')->nullable(); // Commentaires utilisateur
            $table->json('seat_details')->nullable(); // Détails de la place (type, prix, etc.)
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['event_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

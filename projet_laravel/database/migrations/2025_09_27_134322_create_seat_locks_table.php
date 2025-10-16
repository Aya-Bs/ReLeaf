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
        Schema::create('seat_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('seat_number');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Index pour la performance et unicité
            $table->unique(['event_id', 'seat_number']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_locks');
    }
};

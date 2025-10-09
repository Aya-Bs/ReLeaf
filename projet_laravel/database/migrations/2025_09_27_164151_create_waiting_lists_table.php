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
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_email');
            $table->enum('status', ['waiting', 'promoted', 'cancelled'])->default('waiting');
            $table->integer('position')->default(1); // Position dans la liste d'attente
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('promoted_at')->nullable();
            $table->foreignId('promoted_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Un utilisateur ne peut être qu'une fois dans la liste d'attente pour un événement
            $table->unique(['user_id', 'event_id']);
            $table->index(['event_id', 'status', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');
    }
};
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('date');
            $table->string('location'); // Keep as string for now, or change to location_id if you have a locations table
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'cancelled', 'rejected'])->default('draft');
            $table->json('images')->nullable(); // Changed from 'image' to 'images' and made it JSON
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('duration')->nullable();
            $table->timestamps();

            $table->index(['date', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
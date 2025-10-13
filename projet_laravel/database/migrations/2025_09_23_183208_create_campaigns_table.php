<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['reforestation', 'nettoyage', 'sensibilisation', 'recyclage', 'biodiversite', 'energie_renouvelable', 'autre'])->default('autre');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('goal', 12, 2)->nullable();
            $table->decimal('funds_raised', 12, 2)->default(0);
            $table->integer('participants_count')->default(0);
            $table->text('environmental_impact')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('visibility')->default(true);
            $table->json('tags')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed', 'cancelled'])->default('active');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};
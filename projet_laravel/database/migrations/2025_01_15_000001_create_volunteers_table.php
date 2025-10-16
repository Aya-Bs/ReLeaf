<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('skills')->nullable(); // ['gardening', 'coordination', 'first_aid', etc.]
            $table->json('availability')->nullable(); // [{'day': 'monday', 'start': '09:00', 'end': '17:00'}, ...]
            $table->enum('experience_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->json('preferred_regions')->nullable(); // ['Tunis', 'Sfax', 'Sousse', etc.]
            $table->integer('max_hours_per_week')->default(20);
            $table->string('emergency_contact')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('bio')->nullable();
            $table->text('motivation')->nullable();
            $table->text('previous_volunteer_experience')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'experience_level']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('volunteers');
    }
};

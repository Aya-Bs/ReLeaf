<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('participation'); // Type de certification
            $table->integer('points_earned')->default(0); // Points écologiques gagnés
            $table->timestamp('date_awarded'); // Date d'attribution
            $table->foreignId('issued_by')->constrained('users'); // Admin qui émet
            $table->string('certificate_code')->unique(); // Code unique du certificat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};

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
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('motivation');
            $table->text('additional_info')->nullable();
            $table->enum('sponsorship_type', ['argent', 'materiel', 'service'])->default('argent');
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('deletion_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['sponsorship_type', 'status']);
            $table->index('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};

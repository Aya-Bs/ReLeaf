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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['individual', 'sponsor'])->default('individual');
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sponsor_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['event_id', 'created_at']);
            $table->index('donor_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

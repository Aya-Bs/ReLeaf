<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained()->onDelete('cascade');
            $table->string('assignable_type'); // 'App\Models\Event' or 'App\Models\Campaign'
            $table->unsignedBigInteger('assignable_id');
            $table->string('role'); // 'coordinator', 'helper', 'specialist', 'supervisor', 'organizer'
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('assigned_at')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('hours_committed')->default(0);
            $table->integer('hours_worked')->default(0);
            $table->text('notes')->nullable();
            $table->decimal('rating', 2, 1)->nullable(); // 0.0 to 5.0
            $table->text('feedback')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['assignable_type', 'assignable_id']);
            $table->index(['volunteer_id', 'status']);
            $table->index(['status', 'start_date']);
            $table->index(['role', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};

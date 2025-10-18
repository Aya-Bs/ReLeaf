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
        Schema::table('donations', function (Blueprint $table) {
            // Add missing foreign key to users (nullable for guests)
            if (!Schema::hasColumn('donations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('event_id')->constrained()->onDelete('set null');
            }

            // Add currency (3-letter code)
            if (!Schema::hasColumn('donations', 'currency')) {
                $table->string('currency', 3)->default('EUR')->after('amount');
            }

            // Add notes field (separate from legacy 'message' column if present)
            if (!Schema::hasColumn('donations', 'notes')) {
                $table->text('notes')->nullable()->after('payment_reference');
            }

            // Add donated_at timestamp (separate from created_at for explicit donation time tracking)
            if (!Schema::hasColumn('donations', 'donated_at')) {
                $table->timestamp('donated_at')->nullable()->after('processed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('donations', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('donations', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('donations', 'donated_at')) {
                $table->dropColumn('donated_at');
            }
        });
    }
};

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
        Schema::table('volunteers', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('status');
            $table->integer('ranking')->nullable()->after('points');
            
            $table->index(['points', 'ranking']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropIndex(['points', 'ranking']);
            $table->dropColumn(['points', 'ranking']);
        });
    }
};

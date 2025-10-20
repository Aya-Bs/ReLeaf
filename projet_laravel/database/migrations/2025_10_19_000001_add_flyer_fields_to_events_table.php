<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('flyer_path')->nullable();
            $table->string('flyer_image_path')->nullable();
            $table->timestamp('flyer_generated_at')->nullable();
            $table->string('flyer_status')->default('pending');
            $table->string('flyer_style')->nullable();
            $table->text('flyer_prompt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'flyer_path',
                'flyer_image_path',
                'flyer_generated_at',
                'flyer_status',
                'flyer_style',
                'flyer_prompt'
            ]);
        });
    }
};

<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_social_shares_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('social_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('platform', ['facebook', 'twitter', 'linkedin', 'instagram', 'whatsapp']);
            $table->string('share_url')->nullable();
            $table->json('share_data')->nullable();
            $table->timestamp('shared_at');
            $table->timestamps();
            
            $table->index(['event_id', 'platform']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_shares');
    }
};
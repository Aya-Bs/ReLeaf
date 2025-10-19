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
<<<<<<<< HEAD:projet_laravel/database/migrations/2025_09_26_200323_create_blogs_table.php
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('author_id');
            $table->timestamp('date_posted')->nullable();
            $table->string('image_url')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
========
        Schema::table('blogs', function (Blueprint $table) {
            //
>>>>>>>> origin/sponsor-donations:projet_laravel/database/migrations/2025_10_14_134611_remove_author_id_from_blogs_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:projet_laravel/database/migrations/2025_09_26_200323_create_blogs_table.php
        Schema::dropIfExists('blogs');
========
        Schema::table('blogs', function (Blueprint $table) {
            //
        });
>>>>>>>> origin/sponsor-donations:projet_laravel/database/migrations/2025_10_14_134611_remove_author_id_from_blogs_table.php
    }
};

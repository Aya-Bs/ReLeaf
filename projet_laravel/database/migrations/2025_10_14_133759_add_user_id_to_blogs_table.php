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
<<<<<<<< HEAD:projet_laravel/database/migrations/2025_09_28_112259_add_user_id_to_sponsors_table.php
        Schema::table('sponsors', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
========
        Schema::table('blogs', function (Blueprint $table) {
            //
>>>>>>>> origin/sponsor-donations:projet_laravel/database/migrations/2025_10_14_133759_add_user_id_to_blogs_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:projet_laravel/database/migrations/2025_09_28_112259_add_user_id_to_sponsors_table.php
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
========
        Schema::table('blogs', function (Blueprint $table) {
            //
>>>>>>>> origin/sponsor-donations:projet_laravel/database/migrations/2025_10_14_133759_add_user_id_to_blogs_table.php
        });
    }
};

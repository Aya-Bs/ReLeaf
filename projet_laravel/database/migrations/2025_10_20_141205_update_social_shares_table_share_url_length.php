<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('social_shares', function (Blueprint $table) {
            // Change share_url to TEXT to handle long URLs
            $table->text('share_url')->change();
        });
    }

    public function down()
    {
        Schema::table('social_shares', function (Blueprint $table) {
            // Revert back to string if needed
            $table->string('share_url', 500)->change();
        });
    }
};
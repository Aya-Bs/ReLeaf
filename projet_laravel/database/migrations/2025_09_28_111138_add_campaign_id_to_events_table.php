<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained('campaigns')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropColumn('campaign_id');
        });
    }
};
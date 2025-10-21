<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('payment_method');
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id');
            $table->string('receipt_url')->nullable()->after('stripe_payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['stripe_session_id', 'stripe_payment_intent_id', 'receipt_url']);
        });
    }
};

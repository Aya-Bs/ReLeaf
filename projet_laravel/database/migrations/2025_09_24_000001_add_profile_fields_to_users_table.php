<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('email');
            $table->string('phone')->nullable()->change();
            $table->string('city')->nullable()->after('phone');
            $table->string('country')->nullable()->after('city');
            $table->boolean('is_email_verified')->default(false)->after('email_verified_at');
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
        });

        // Créer la table pour l'historique des connexions
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('location')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'city',
                'country',
                'is_email_verified',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_enabled',
            ]);
        });

        Schema::dropIfExists('login_history');
    }
};

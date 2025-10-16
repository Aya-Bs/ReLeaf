<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity_needed')->default(0);
            $table->integer('quantity_pledged')->default(0);
            $table->string('unit')->default('unité');
            $table->string('provider')->nullable();
            $table->enum('status', ['needed', 'pledged', 'received', 'in_use'])->default('needed');
            $table->enum('resource_type', ['money', 'food', 'clothing', 'medical', 'equipment', 'human', 'other']);
            $table->enum('category', ['materiel', 'financier', 'humain', 'technique'])->default('materiel');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('notes')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resources');
    }
};

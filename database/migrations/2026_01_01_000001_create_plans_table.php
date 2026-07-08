<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 12, 2)->default(0.00);
            $table->decimal('price_yearly', 12, 2)->default(0.00);
            $table->integer('max_users')->default(1);
            $table->integer('max_outlets')->default(1);
            $table->integer('max_storage_gb')->default(1);
            $table->integer('trial_days')->default(14);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

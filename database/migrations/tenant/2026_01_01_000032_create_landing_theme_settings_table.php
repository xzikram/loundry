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
        Schema::create('landing_theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color')->default('#1E3A5F');
            $table->string('secondary_color')->default('#2A5082');
            $table->string('accent_color')->default('#D4A853');
            $table->string('background_color')->default('#F8F9FC');
            $table->string('surface_color')->default('#FFFFFF');
            $table->string('text_color')->default('#4A5568');
            $table->string('heading_color')->default('#1A1D23');
            $table->string('heading_font')->default('Outfit');
            $table->string('body_font')->default('Outfit');
            $table->string('button_style')->default('rounded-xl'); // rounded-none, rounded-md, rounded-xl, rounded-full
            $table->string('border_radius')->default('12px');
            $table->string('container_width')->default('max-w-6xl');
            $table->json('custom_settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_theme_settings');
    }
};

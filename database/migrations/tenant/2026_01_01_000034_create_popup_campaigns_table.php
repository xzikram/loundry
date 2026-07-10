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
        Schema::create('popup_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->nullable()->constrained('landing_pages')->onDelete('cascade');
            $table->string('name');
            $table->string('popup_type')->default('center_modal'); // center_modal, full_screen, bottom_banner, slide_in_right
            $table->json('content')->nullable(); // title, description, image_url, button_text, button_url
            $table->json('settings')->nullable(); // bg_color, text_color, delay_seconds, scroll_percent
            $table->string('trigger_type')->default('delay'); // immediately, delay, scroll, exit_intent
            $table->string('trigger_value')->nullable(); // e.g. "5" (seconds), "50" (percent)
            $table->string('frequency_type')->default('once_per_session'); // every_visit, once_per_session, once_per_day, once_per_week, only_once
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_campaigns');
    }
};

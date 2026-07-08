<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('central_user_id')->nullable()->constrained('central_users')->onDelete('set null'); // if replied by super admin
            $table->enum('replier_type', ['admin', 'tenant'])->default('tenant');
            $table->string('replier_name'); // Name of person replying
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false); // Admin-only visible notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
    }
};

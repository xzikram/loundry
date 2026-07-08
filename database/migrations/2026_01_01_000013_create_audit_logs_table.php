<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->string('event'); // created, updated, deleted, login, etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('user_type')->nullable(); // central_user or tenant_user
            $table->unsignedBigInteger('user_id')->nullable(); // user id
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('tenant_id')->nullable(); // if scoped to tenant
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
